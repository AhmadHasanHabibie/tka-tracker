<?php

namespace App\Http\Controllers;

use App\Models\StudyXpLog;
use App\Services\XpService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudyPetController extends Controller
{
    /**
     * Display the Study Pet dashboard.
     */
    public function index()
    {
        $userId = auth()->id();

        // Award daily login XP if not awarded yet today
        XpService::awardLoginXp();

        $levelsConfig = config('study_pet.levels', []);
        $totalXP = (int) StudyXpLog::where('user_id', $userId)->sum('xp');

        // Determine current level and next level
        $currentLevelNum = 1;
        $currentLevelInfo = $levelsConfig[1];
        $nextLevelInfo = null;

        foreach ($levelsConfig as $lvlNum => $info) {
            if ($totalXP >= $info['min_xp']) {
                $currentLevelNum = $lvlNum;
                $currentLevelInfo = $info;
            }
        }

        if (isset($levelsConfig[$currentLevelNum + 1])) {
            $nextLevelInfo = $levelsConfig[$currentLevelNum + 1];
        }

        // Calculate progress bar percentage and remaining XP
        if ($nextLevelInfo) {
            $minXP = $currentLevelInfo['min_xp'];
            $maxXP = $nextLevelInfo['min_xp'];
            $range = $maxXP - $minXP;
            $gained = $totalXP - $minXP;
            $progressPercent = $range > 0 ? min(100, max(0, round(($gained / $range) * 100))) : 100;
            $xpNeededForNext = $maxXP - $totalXP;
        } else {
            $progressPercent = 100;
            $xpNeededForNext = 0;
        }

        // Streaks
        $currentStreak = StudyXpLog::getCurrentStreak($userId);
        $bestStreak = StudyXpLog::getBestStreak($userId);

        // Today's XP Logs & Count
        $todayStr = Carbon::today()->toDateString();
        $todayXpLogs = StudyXpLog::where('user_id', $userId)
            ->where('activity_date', $todayStr)
            ->orderBy('id', 'desc')
            ->get();
        $todayXpTotal = $todayXpLogs->sum('xp');
        $todayActivitiesCount = $todayXpLogs->count();

        // Monthly Stats & Calendar Grid
        $now = Carbon::now();
        $monthlyActiveDays = StudyXpLog::where('user_id', $userId)
            ->whereMonth('activity_date', $now->month)
            ->whereYear('activity_date', $now->year)
            ->distinct('activity_date')
            ->count('activity_date');

        $activeDatesInMonth = StudyXpLog::where('user_id', $userId)
            ->whereMonth('activity_date', $now->month)
            ->whereYear('activity_date', $now->year)
            ->pluck('activity_date')
            ->map(fn($d) => Carbon::parse($d)->day)
            ->unique()
            ->toArray();

        $daysInMonth = $now->daysInMonth;
        $calendarGrid = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $calendarGrid[] = [
                'day' => $day,
                'is_active' => in_array($day, $activeDatesInMonth),
                'is_today' => ($day === $now->day),
            ];
        }

        // Recent XP History
        $recentXpLogs = StudyXpLog::where('user_id', $userId)
            ->orderBy('activity_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return view('study_pet.index', compact(
            'totalXP',
            'currentLevelNum',
            'currentLevelInfo',
            'nextLevelInfo',
            'progressPercent',
            'xpNeededForNext',
            'currentStreak',
            'bestStreak',
            'monthlyActiveDays',
            'todayXpLogs',
            'todayXpTotal',
            'todayActivitiesCount',
            'calendarGrid',
            'recentXpLogs',
            'now'
        ));
    }
}
