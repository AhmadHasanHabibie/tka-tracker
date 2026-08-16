<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\TodoTask;
use Illuminate\Http\Request;

class TodoTaskController extends Controller
{
    /**
     * Display todolist filtered by materi.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $materis = Materi::where('user_id', $userId)->with('subject')->orderBy('title')->get();
        $selectedMateriId = $request->query('materi_id');

        $query = TodoTask::where('user_id', $userId)->with('materi.subject')->latest();

        if ($selectedMateriId) {
            $query->where('materi_id', $selectedMateriId);
            $selectedMateri = Materi::where('user_id', $userId)->with('subject')->find($selectedMateriId);
        } else {
            $selectedMateri = null;
        }

        $todoTasks = $query->get();

        return view('todolist.index', compact('materis', 'todoTasks', 'selectedMateriId', 'selectedMateri'));
    }

    /**
     * Store new todo task under a materi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        TodoTask::create($validated);

        return redirect()->route('todolist.index', ['materi_id' => $request->materi_id])
            ->with('success', 'To-Do item berhasil ditambahkan!');
    }

    /**
     * Toggle status or complete a todo task.
     */
    public function complete(TodoTask $todoTask)
    {
        if ($todoTask->user_id && $todoTask->user_id !== auth()->id()) {
            abort(403);
        }

        $newStatus = $todoTask->status === 'completed' ? 'pending' : 'completed';
        $todoTask->update(['status' => $newStatus]);

        if ($newStatus === 'completed') {
            $awarded = \App\Services\XpService::awardTodoXp($todoTask);
            $message = $awarded 
                ? '🎉 To-Do selesai! (+10 XP tercatat)' 
                : 'To-Do ditandai selesai!';
        } else {
            $message = 'To-Do dikembalikan ke pending.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show edit form for a todo task.
     */
    public function edit(TodoTask $todoTask)
    {
        if ($todoTask->user_id && $todoTask->user_id !== auth()->id()) {
            abort(403);
        }

        $userId = auth()->id();
        $materis = Materi::where('user_id', $userId)->with('subject')->orderBy('title')->get();
        return view('todolist.edit', compact('todoTask', 'materis'));
    }

    /**
     * Update a todo task.
     */
    public function update(Request $request, TodoTask $todoTask)
    {
        if ($todoTask->user_id && $todoTask->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,completed',
        ]);

        $todoTask->update($validated);

        return redirect()->route('todolist.index', ['materi_id' => $todoTask->materi_id])
            ->with('success', 'To-Do item berhasil diperbarui!');
    }

    /**
     * Delete a todo task.
     */
    public function destroy(TodoTask $todoTask)
    {
        if ($todoTask->user_id && $todoTask->user_id !== auth()->id()) {
            abort(403);
        }

        $materiId = $todoTask->materi_id;
        $todoTask->delete();

        return redirect()->route('todolist.index', ['materi_id' => $materiId])
            ->with('success', 'To-Do item berhasil dihapus!');
    }
}
