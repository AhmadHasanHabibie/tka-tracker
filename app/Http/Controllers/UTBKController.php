<?php

namespace App\Http\Controllers;

use App\Models\UTBKTryout;
use App\Models\UTBKSubtestScore;
use Illuminate\Http\Request;

class UTBKController extends Controller
{
    /**
     * Display UTBK Score Tracker dashboard.
     */
    public function index()
    {
        $userId = auth()->id();
        $subtests = config('utbk.subtests', []);

        // Fetch all tryouts ordered chronologically for graphs
        $tryouts = UTBKTryout::where('user_id', $userId)
            ->with('subtestScores')
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // History table ordered newest to oldest
        $historyTryouts = UTBKTryout::where('user_id', $userId)
            ->with('subtestScores')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalTryouts = $tryouts->count();
        $latestTryout = $tryouts->last();
        $previousTryout = $totalTryouts >= 2 ? $tryouts->get($totalTryouts - 2) : null;

        $latestScore = $latestTryout ? $latestTryout->overall_score : null;
        $highestScore = $totalTryouts > 0 ? $tryouts->max('overall_score') : null;

        $scoreChange = null;
        if ($latestTryout && $previousTryout) {
            $scoreChange = round($latestTryout->overall_score - $previousTryout->overall_score, 2);
        }

        // Graph 1: Overall Score Trend (Line Chart Data)
        $overallLabels = $tryouts->map(fn($t, $idx) => 'Tryout ' . ($idx + 1))->toArray();
        $overallScores = $tryouts->pluck('overall_score')->toArray();

        // Graph 2: Subtest Trends per Subtest
        $subtestTrends = [];
        foreach ($subtests as $key => $label) {
            $subtestTrends[$key] = [];
            foreach ($tryouts as $to) {
                $subScore = $to->subtestScores->firstWhere('subtest', $key);
                $subtestTrends[$key][] = $subScore ? (float) $subScore->score : 0;
            }
        }

        // Graph 3: Latest Subtest Comparison (Bar Chart) & Areas to Watch & Differences
        $latestSubtestScores = [];
        $areasToWatch = [];
        $subtestChanges = [];

        if ($latestTryout) {
            foreach ($subtests as $key => $label) {
                $subScore = $latestTryout->subtestScores->firstWhere('subtest', $key);
                $val = $subScore ? (float) $subScore->score : 0;
                $latestSubtestScores[$key] = [
                    'label' => $label,
                    'score' => $val,
                ];
            }

            // Lowest 3 subtests from latest tryout
            $sortedSubtests = collect($latestSubtestScores)->sortBy('score')->take(3);
            $areasToWatch = $sortedSubtests->toArray();

            // Differences from previous tryout
            if ($previousTryout) {
                foreach ($subtests as $key => $label) {
                    $prevScoreObj = $previousTryout->subtestScores->firstWhere('subtest', $key);
                    $prevVal = $prevScoreObj ? (float) $prevScoreObj->score : 0;
                    $currVal = $latestSubtestScores[$key]['score'];
                    $diff = round($currVal - $prevVal, 2);

                    $subtestChanges[$key] = [
                        'label' => $label,
                        'previous' => $prevVal,
                        'current' => $currVal,
                        'diff' => $diff,
                    ];
                }
            }
        }

        return view('utbk.index', compact(
            'subtests',
            'tryouts',
            'historyTryouts',
            'totalTryouts',
            'latestTryout',
            'previousTryout',
            'latestScore',
            'highestScore',
            'scoreChange',
            'overallLabels',
            'overallScores',
            'subtestTrends',
            'latestSubtestScores',
            'areasToWatch',
            'subtestChanges'
        ));
    }

    /**
     * Show form to create new tryout result.
     */
    public function create()
    {
        $subtests = config('utbk.subtests', []);
        return view('utbk.create', compact('subtests'));
    }

    /**
     * Store new tryout result with 7 subtest scores.
     */
    public function store(Request $request)
    {
        $subtests = config('utbk.subtests', []);

        $rules = [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'overall_score' => 'required|numeric|min:0|max:1000',
            'notes' => 'nullable|string',
            'subtest_scores' => 'required|array',
        ];

        foreach (array_keys($subtests) as $key) {
            $rules["subtest_scores.{$key}"] = 'required|numeric|min:0|max:1000';
        }

        $validated = $request->validate($rules);

        $tryout = UTBKTryout::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'date' => $validated['date'],
            'overall_score' => $validated['overall_score'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($subtests as $key => $label) {
            UTBKSubtestScore::create([
                'utbk_tryout_id' => $tryout->id,
                'subtest' => $key,
                'score' => $validated['subtest_scores'][$key],
            ]);
        }

        return redirect()->route('utbk.index')
            ->with('success', 'Hasil Tryout "' . $tryout->name . '" berhasil disimpan!');
    }

    /**
     * Display details of a tryout.
     */
    public function show(UTBKTryout $utbkTryout)
    {
        if ($utbkTryout->user_id && $utbkTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $utbkTryout->load('subtestScores');
        $subtests = config('utbk.subtests', []);

        return view('utbk.show', compact('utbkTryout', 'subtests'));
    }

    /**
     * Show edit form for a tryout.
     */
    public function edit(UTBKTryout $utbkTryout)
    {
        if ($utbkTryout->user_id && $utbkTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $utbkTryout->load('subtestScores');
        $subtests = config('utbk.subtests', []);

        return view('utbk.edit', compact('utbkTryout', 'subtests'));
    }

    /**
     * Update tryout and subtest scores.
     */
    public function update(Request $request, UTBKTryout $utbkTryout)
    {
        if ($utbkTryout->user_id && $utbkTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $subtests = config('utbk.subtests', []);

        $rules = [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'overall_score' => 'required|numeric|min:0|max:1000',
            'notes' => 'nullable|string',
            'subtest_scores' => 'required|array',
        ];

        foreach (array_keys($subtests) as $key) {
            $rules["subtest_scores.{$key}"] = 'required|numeric|min:0|max:1000';
        }

        $validated = $request->validate($rules);

        $utbkTryout->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'overall_score' => $validated['overall_score'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($subtests as $key => $label) {
            UTBKSubtestScore::updateOrCreate(
                [
                    'utbk_tryout_id' => $utbkTryout->id,
                    'subtest' => $key,
                ],
                [
                    'score' => $validated['subtest_scores'][$key],
                ]
            );
        }

        return redirect()->route('utbk.index')
            ->with('success', 'Hasil Tryout "' . $utbkTryout->name . '" berhasil diperbarui!');
    }

    /**
     * Delete a tryout and its subtest scores.
     */
    public function destroy(UTBKTryout $utbkTryout)
    {
        if ($utbkTryout->user_id && $utbkTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $name = $utbkTryout->name;
        $utbkTryout->delete();

        return redirect()->route('utbk.index')
            ->with('success', 'Hasil Tryout "' . $name . '" berhasil dihapus!');
    }
}
