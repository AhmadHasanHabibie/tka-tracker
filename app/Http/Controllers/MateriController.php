<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Subject;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    /**
     * Display list of materi & form to create new materi.
     */
    public function index()
    {
        $userId = auth()->id();
        $materis = Materi::where('user_id', $userId)
            ->with(['subject', 'todoTasks' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->latest()
            ->get();
        $subjects = Subject::orderBy('name')->get();

        return view('materi.index', compact('materis', 'subjects'));
    }

    /**
     * Store a newly created materi with custom or selected subject name.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::firstOrCreate([
            'name' => trim($validated['subject_name']),
        ]);

        $materi = Materi::create([
            'user_id' => auth()->id(),
            'subject_id' => $subject->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('todolist.index', ['materi_id' => $materi->id])
            ->with('success', 'Materi "' . $materi->title . '" [' . $subject->name . '] berhasil dibuat! Silakan tambahkan To-Do list kamu.');
    }

    /**
     * Show form for editing materi.
     */
    public function edit(Materi $materi)
    {
        if ($materi->user_id && $materi->user_id !== auth()->id()) {
            abort(403);
        }
        $subjects = Subject::orderBy('name')->get();
        return view('materi.edit', compact('materi', 'subjects'));
    }

    /**
     * Update materi details.
     */
    public function update(Request $request, Materi $materi)
    {
        if ($materi->user_id && $materi->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::firstOrCreate([
            'name' => trim($validated['subject_name']),
        ]);

        $materi->update([
            'subject_id' => $subject->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Delete a materi.
     */
    public function destroy(Materi $materi)
    {
        if ($materi->user_id && $materi->user_id !== auth()->id()) {
            abort(403);
        }

        $title = $materi->title;
        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi "' . $title . '" berhasil dihapus!');
    }
}
