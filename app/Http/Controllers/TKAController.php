<?php

namespace App\Http\Controllers;

use App\Models\TKATryout;
use App\Models\TKASubject;
use App\Models\TKASubjectScore;
use Illuminate\Http\Request;

class TKAController extends Controller
{
    /**
     * Display TKA Analysis dashboard and charts.
     */
    public function index()
    {
        $userId = auth()->id();
        $mandatorySubjects = TKASubject::where('type', 'mandatory')->where('is_active', true)->get();
        $choiceSubjects = TKASubject::where('type', 'choice')->where('is_active', true)->orderBy('name', 'asc')->get();

        // Fetch all TKA tryouts chronologically for charts
        $tryouts = TKATryout::where('user_id', $userId)
            ->with('subjectScores')
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // History table ordered newest to oldest
        $historyTryouts = TKATryout::where('user_id', $userId)
            ->with('subjectScores')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalTryouts = $tryouts->count();
        $latestTryout = $tryouts->last();
        $previousTryout = $totalTryouts >= 2 ? $tryouts->get($totalTryouts - 2) : null;

        $highestSubject = null;
        $lowestSubject = null;
        $latestSubjectScores = [];
        $areasToWatch = [];
        $subjectChanges = [];
        $biggestIncrease = null;
        $biggestDecrease = null;

        if ($latestTryout) {
            $latestScoresCollection = $latestTryout->subjectScores;

            // Latest subtest scores list
            foreach ($latestScoresCollection as $ss) {
                $latestSubjectScores[] = [
                    'name' => $ss->subject_name,
                    'type' => $ss->subject_type,
                    'score' => (float) $ss->score,
                ];
            }

            // Highest & lowest in latest tryout
            $sortedLatest = collect($latestSubjectScores)->sortByDesc('score')->values();
            $highestSubject = $sortedLatest->first();
            $lowestSubject = $sortedLatest->last();

            // Areas to watch (Lowest 3 subjects)
            $areasToWatch = collect($latestSubjectScores)->sortBy('score')->take(3)->values()->toArray();

            // Changes from previous tryout
            if ($previousTryout) {
                $prevScoresCollection = $previousTryout->subjectScores;

                foreach ($latestSubjectScores as $curr) {
                    $name = $curr['name'];
                    $prevItem = $prevScoresCollection->firstWhere('subject_name', $name);

                    if ($prevItem) {
                        $prevScore = (float) $prevItem->score;
                        $currScore = $curr['score'];
                        $diff = round($currScore - $prevScore, 2);

                        $itemChange = [
                            'name' => $name,
                            'previous' => $prevScore,
                            'current' => $currScore,
                            'diff' => $diff,
                        ];

                        $subjectChanges[] = $itemChange;
                    }
                }

                // Biggest increase & decrease
                $changeCollection = collect($subjectChanges);
                $increaseCollection = $changeCollection->where('diff', '>', 0)->sortByDesc('diff');
                $decreaseCollection = $changeCollection->where('diff', '<', 0)->sortBy('diff');

                $biggestIncrease = $increaseCollection->first();
                $biggestDecrease = $decreaseCollection->first();
            }
        }

        // Line Chart Trends Data (All distinct subject names across tryouts)
        $allSubjectNames = TKASubjectScore::distinct()->pluck('subject_name')->toArray();
        $chartLabels = $tryouts->map(fn($t, $idx) => 'TKA ' . ($idx + 1))->toArray();

        $subjectTrends = [];
        foreach ($allSubjectNames as $subjectName) {
            $subjectTrends[$subjectName] = [];
            foreach ($tryouts as $to) {
                $scoreObj = $to->subjectScores->firstWhere('subject_name', $subjectName);
                $subjectTrends[$subjectName][] = $scoreObj ? (float) $scoreObj->score : null;
            }
        }

        return view('tka.index', compact(
            'mandatorySubjects',
            'choiceSubjects',
            'tryouts',
            'historyTryouts',
            'totalTryouts',
            'latestTryout',
            'previousTryout',
            'highestSubject',
            'lowestSubject',
            'latestSubjectScores',
            'areasToWatch',
            'subjectChanges',
            'biggestIncrease',
            'biggestDecrease',
            'allSubjectNames',
            'chartLabels',
            'subjectTrends'
        ));
    }

    /**
     * Show form to add TKA tryout result.
     */
    public function create()
    {
        $mandatory = TKASubject::where('type', 'mandatory')->where('is_active', true)->get();
        $choice = TKASubject::where('type', 'choice')->where('is_active', true)->orderBy('name', 'asc')->get();

        return view('tka.create', compact('mandatory', 'choice'));
    }

    /**
     * Store new TKA tryout result with mandatory & choice subjects.
     */
    public function store(Request $request)
    {
        $mandatorySubjects = TKASubject::where('type', 'mandatory')->where('is_active', true)->get();
        $choiceSubjects = TKASubject::where('type', 'choice')->where('is_active', true)->get();
        $choiceIds = $choiceSubjects->pluck('id')->toArray();

        // Duplicate choice subject check
        if ($request->choice_1_id && $request->choice_2_id && $request->choice_1_id == $request->choice_2_id) {
            return redirect()->back()->withInput()->withErrors([
                'choice_2_id' => 'Mata pelajaran pilihan harus berbeda.',
            ]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'choice_1_id' => 'required|integer|in:' . implode(',', $choiceIds),
            'choice_1_score' => 'required|numeric|min:0|max:100',
            'choice_2_id' => 'required|integer|in:' . implode(',', $choiceIds),
            'choice_2_score' => 'required|numeric|min:0|max:100',
        ];

        foreach ($mandatorySubjects as $mSubject) {
            $rules["mandatory_scores.{$mSubject->id}"] = 'required|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

        $tryout = TKATryout::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Save Mandatory Subjects
        foreach ($mandatorySubjects as $mSubject) {
            TKASubjectScore::create([
                'tka_tryout_id' => $tryout->id,
                'tka_subject_id' => $mSubject->id,
                'subject_name' => $mSubject->name,
                'subject_type' => 'mandatory',
                'score' => $validated['mandatory_scores'][$mSubject->id],
            ]);
        }

        // Save Choice Subject 1
        $c1Subject = TKASubject::find($validated['choice_1_id']);
        TKASubjectScore::create([
            'tka_tryout_id' => $tryout->id,
            'tka_subject_id' => $c1Subject->id,
            'subject_name' => $c1Subject->name,
            'subject_type' => 'choice',
            'score' => $validated['choice_1_score'],
        ]);

        // Save Choice Subject 2
        $c2Subject = TKASubject::find($validated['choice_2_id']);
        TKASubjectScore::create([
            'tka_tryout_id' => $tryout->id,
            'tka_subject_id' => $c2Subject->id,
            'subject_name' => $c2Subject->name,
            'subject_type' => 'choice',
            'score' => $validated['choice_2_score'],
        ]);

        return redirect()->route('tka.index')
            ->with('success', 'Hasil TKA "' . $tryout->name . '" berhasil disimpan!');
    }

    /**
     * Display details of a TKA tryout.
     */
    public function show(TKATryout $tkaTryout)
    {
        if ($tkaTryout->user_id && $tkaTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $tkaTryout->load('subjectScores');
        return view('tka.show', compact('tkaTryout'));
    }

    /**
     * Show edit form for a TKA tryout.
     */
    public function edit(TKATryout $tkaTryout)
    {
        if ($tkaTryout->user_id && $tkaTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $tkaTryout->load('subjectScores');
        $mandatory = TKASubject::where('type', 'mandatory')->where('is_active', true)->get();
        $choice = TKASubject::where('type', 'choice')->where('is_active', true)->orderBy('name', 'asc')->get();

        $choiceScores = $tkaTryout->subjectScores->where('subject_type', 'choice')->values();
        $choice1 = $choiceScores->get(0);
        $choice2 = $choiceScores->get(1);

        return view('tka.edit', compact('tkaTryout', 'mandatory', 'choice', 'choice1', 'choice2'));
    }

    /**
     * Update TKA tryout and its subject scores.
     */
    public function update(Request $request, TKATryout $tkaTryout)
    {
        if ($tkaTryout->user_id && $tkaTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $mandatorySubjects = TKASubject::where('type', 'mandatory')->where('is_active', true)->get();
        $choiceSubjects = TKASubject::where('type', 'choice')->where('is_active', true)->get();
        $choiceIds = $choiceSubjects->pluck('id')->toArray();

        // Duplicate choice subject check
        if ($request->choice_1_id && $request->choice_2_id && $request->choice_1_id == $request->choice_2_id) {
            return redirect()->back()->withInput()->withErrors([
                'choice_2_id' => 'Mata pelajaran pilihan harus berbeda.',
            ]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'choice_1_id' => 'required|integer|in:' . implode(',', $choiceIds),
            'choice_1_score' => 'required|numeric|min:0|max:100',
            'choice_2_id' => 'required|integer|in:' . implode(',', $choiceIds),
            'choice_2_score' => 'required|numeric|min:0|max:100',
        ];

        foreach ($mandatorySubjects as $mSubject) {
            $rules["mandatory_scores.{$mSubject->id}"] = 'required|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

        $tkaTryout->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Delete existing subject scores and recreate
        $tkaTryout->subjectScores()->delete();

        // Save Mandatory Subjects
        foreach ($mandatorySubjects as $mSubject) {
            TKASubjectScore::create([
                'tka_tryout_id' => $tkaTryout->id,
                'tka_subject_id' => $mSubject->id,
                'subject_name' => $mSubject->name,
                'subject_type' => 'mandatory',
                'score' => $validated['mandatory_scores'][$mSubject->id],
            ]);
        }

        // Save Choice Subject 1
        $c1Subject = TKASubject::find($validated['choice_1_id']);
        TKASubjectScore::create([
            'tka_tryout_id' => $tkaTryout->id,
            'tka_subject_id' => $c1Subject->id,
            'subject_name' => $c1Subject->name,
            'subject_type' => 'choice',
            'score' => $validated['choice_1_score'],
        ]);

        // Save Choice Subject 2
        $c2Subject = TKASubject::find($validated['choice_2_id']);
        TKASubjectScore::create([
            'tka_tryout_id' => $tkaTryout->id,
            'tka_subject_id' => $c2Subject->id,
            'subject_name' => $c2Subject->name,
            'subject_type' => 'choice',
            'score' => $validated['choice_2_score'],
        ]);

        return redirect()->route('tka.index')
            ->with('success', 'Hasil TKA "' . $tkaTryout->name . '" berhasil diperbarui!');
    }

    /**
     * Delete TKA tryout and its subject scores.
     */
    public function destroy(TKATryout $tkaTryout)
    {
        if ($tkaTryout->user_id && $tkaTryout->user_id !== auth()->id()) {
            abort(403);
        }

        $name = $tkaTryout->name;
        $tkaTryout->delete();

        return redirect()->route('tka.index')
            ->with('success', 'Hasil TKA "' . $name . '" berhasil dihapus!');
    }
}
