<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    /**
     * Log user or system action into activity_logs table.
     */
    public static function log(string $action, ?string $description = null): void
    {
        try {
            $user = auth()->user();
            ActivityLog::create([
                'user_id' => $user?->id,
                'username' => $user?->username ?? 'Guest',
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Suppress log failures to prevent blocking main requests
        }
    }
}
