<?php

namespace App\Services;

use App\Models\StudyXpLog;
use App\Models\TodoTask;
use Carbon\Carbon;

class XpService
{
    /**
     * Award +5 XP for the first daily login.
     */
    public static function awardLoginXp(): bool
    {
        $userId = auth()->id();
        if (!$userId) {
            return false;
        }

        $todayStr = Carbon::today()->toDateString();

        $alreadyAwarded = StudyXpLog::where('user_id', $userId)
            ->where('source', 'login')
            ->whereDate('activity_date', $todayStr)
            ->exists();

        if (!$alreadyAwarded) {
            StudyXpLog::create([
                'user_id' => $userId,
                'source' => 'login',
                'description' => 'Login Pertama Hari Ini (+5 XP)',
                'xp' => 5,
                'activity_date' => $todayStr,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Award +10 XP for the first completion of a To-Do item.
     */
    public static function awardTodoXp(TodoTask $task): bool
    {
        $userId = $task->user_id ?? auth()->id();
        if (!$userId) {
            return false;
        }

        $alreadyAwarded = StudyXpLog::where('user_id', $userId)
            ->where('source', 'todo_completed')
            ->where('reference_id', $task->id)
            ->exists();

        if (!$alreadyAwarded) {
            $task->loadMissing('materi');
            $desc = $task->materi ? "{$task->materi->title}: {$task->title}" : $task->title;

            StudyXpLog::create([
                'user_id' => $userId,
                'source' => 'todo_completed',
                'reference_id' => $task->id,
                'description' => $desc,
                'xp' => 10,
                'activity_date' => Carbon::today()->toDateString(),
            ]);
            return true;
        }

        return false;
    }
}
