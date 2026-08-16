<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Materi;
use App\Models\TodoTask;

class DashboardController extends Controller
{
    public function index()
    {
        \App\Services\XpService::awardLoginXp();

        $userId = auth()->id();

        $goal = Goal::where('user_id', $userId)->first();

        $materis = Materi::where('user_id', $userId)
            ->with(['subject', 'todoTasks' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->latest()
            ->get();

        $totalMateri = $materis->count();
        $totalTodos = TodoTask::where('user_id', $userId)->count();
        $completedTodos = TodoTask::where('user_id', $userId)->where('status', 'completed')->count();
        $overallProgress = $totalTodos > 0 ? (int) round(($completedTodos / $totalTodos) * 100) : 0;

        return view('dashboard', compact(
            'goal',
            'materis',
            'totalMateri',
            'totalTodos',
            'completedTodos',
            'overallProgress'
        ));
    }
}
