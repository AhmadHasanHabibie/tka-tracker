<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyXpLog extends Model
{
    use HasFactory;

    protected $table = 'study_xp_logs';

    protected $fillable = [
        'user_id',
        'source',
        'reference_id',
        'description',
        'xp',
        'activity_date',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'xp' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate current streak based on UNIQUE activity_date in XP logs for specific user.
     */
    public static function getCurrentStreak(?int $userId = null): int
    {
        $userId = $userId ?? auth()->id();

        $dates = self::where('user_id', $userId)
            ->select('activity_date')
            ->distinct()
            ->orderBy('activity_date', 'desc')
            ->pluck('activity_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // If user has no XP log today OR yesterday, streak is reset
        if (!in_array($today, $dates) && !in_array($yesterday, $dates)) {
            return 0;
        }

        // Start checking from the most recent active date (today or yesterday)
        $current = in_array($today, $dates) ? Carbon::today() : Carbon::yesterday();
        $streak = 0;

        while (in_array($current->toDateString(), $dates)) {
            $streak++;
            $current->subDay();
        }

        return $streak;
    }

    /**
     * Calculate best (longest) streak achieved in history for specific user.
     */
    public static function getBestStreak(?int $userId = null): int
    {
        $userId = $userId ?? auth()->id();

        $dates = self::where('user_id', $userId)
            ->select('activity_date')
            ->distinct()
            ->orderBy('activity_date', 'asc')
            ->pluck('activity_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $bestStreak = 0;
        $currentStreak = 0;
        $prevDate = null;

        foreach ($dates as $dateStr) {
            $currDate = Carbon::parse($dateStr);

            if ($prevDate === null) {
                $currentStreak = 1;
            } else {
                $diffInDays = $prevDate->diffInDays($currDate);
                if ($diffInDays === 1) {
                    $currentStreak++;
                } else {
                    $currentStreak = 1;
                }
            }

            if ($currentStreak > $bestStreak) {
                $bestStreak = $currentStreak;
            }

            $prevDate = $currDate;
        }

        return $bestStreak;
    }
}
