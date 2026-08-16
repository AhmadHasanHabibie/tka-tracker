<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UTBKTryout;
use App\Models\TKATryout;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\RateLimiter;

class AdminController extends Controller
{
    /**
     * Show PIN entry page for Admin.
     */
    public function showPinForm()
    {
        if (session('admin_pin_verified') === true) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pin');
    }

    /**
     * Verify Admin PIN (131313).
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ], [
            'pin.required' => 'PIN wajib diisi.',
        ]);

        $throttleKey = 'admin_pin:' . $request->ip() . '|' . (auth()->id() ?? 'guest');

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            ActivityLogger::log('Rate Limit PIN Admin', 'Percobaan PIN admin melebihi batas dari IP ' . $request->ip());
            return back()->withErrors(['pin' => 'Terlalu banyak percobaan PIN salah. Silakan coba lagi dalam ' . $seconds . ' detik.']);
        }

        if ($request->pin === '131313') {
            RateLimiter::clear($throttleKey);
            session(['admin_pin_verified' => true]);
            ActivityLogger::log('Verifikasi PIN Admin', 'Admin berhasil memverifikasi PIN 131313');
            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        ActivityLogger::log('Gagal PIN Admin', 'Percobaan PIN admin salah dari IP ' . $request->ip());

        return back()->withErrors(['pin' => 'PIN salah! Masukkan PIN yang benar (131313).']);
    }

    /**
     * Display Admin Dashboard.
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalUtbk = UTBKTryout::count();
        $totalTka = TKATryout::count();
        $totalActivities = ActivityLog::count();

        $maintenanceFile = storage_path('app/maintenance.json');
        $isMaintenanceActive = false;
        if (file_exists($maintenanceFile)) {
            $data = json_decode(file_get_contents($maintenanceFile), true);
            $isMaintenanceActive = !empty($data['is_active']);
        }

        $recentActivities = ActivityLog::with('user')->orderBy('id', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalUtbk',
            'totalTka',
            'totalActivities',
            'isMaintenanceActive',
            'recentActivities'
        ));
    }

    /**
     * Display Activity History Log page.
     */
    public function activities()
    {
        $activities = ActivityLog::with('user')->orderBy('id', 'desc')->paginate(25);
        return view('admin.activities', compact('activities'));
    }

    /**
     * Toggle Maintenance Mode status.
     */
    public function toggleMaintenance(Request $request)
    {
        $maintenanceFile = storage_path('app/maintenance.json');
        $currentState = false;

        if (file_exists($maintenanceFile)) {
            $data = json_decode(file_get_contents($maintenanceFile), true);
            $currentState = !empty($data['is_active']);
        }

        $newState = !$currentState;

        $data = [
            'is_active' => $newState,
            'message' => 'Website UTBK Tracker sedang dalam perbaikan/pemeliharaan berkala. Silakan coba beberapa saat lagi.',
            'updated_at' => now()->toDateTimeString(),
            'updated_by' => auth()->user()?->username,
        ];

        File::put($maintenanceFile, json_encode($data, JSON_PRETTY_PRINT));

        $statusStr = $newState ? 'DILAKUKAN (AKTIF)' : 'DINONAKTIFKAN';
        ActivityLogger::log('Mode Maintenance', 'Admin mengubah mode maintenance menjadi ' . $statusStr);

        return redirect()->back()->with('success', 'Status Maintenance berhasil diubah menjadi ' . $statusStr . '!');
    }

    /**
     * Download database backup (.sql format for phpMyAdmin / MySQL).
     */
    public function downloadBackup()
    {
        ActivityLogger::log('Backup Database', 'Admin mengunduh backup database SQL');

        $driver = config('database.default');
        $pdo = \Illuminate\Support\Facades\DB::getPdo();

        $tables = [
            'users',
            'activity_logs',
            'subjects',
            'materis',
            'todo_tasks',
            'goals',
            'utbk_tryouts',
            'utbk_subtest_scores',
            'tka_subjects',
            'tka_tryouts',
            'tka_subject_scores',
            'study_xp_logs',
        ];

        $sqlContent = "-- UTBK Tracker Database SQL Backup\n";
        $sqlContent .= "-- Exported on: " . date('Y-m-d H:i:s') . "\n";
        $sqlContent .= "-- Driver: " . $driver . "\n\n";
        $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
                continue;
            }

            $sqlContent .= "--\n-- Table structure & data for `$table`\n--\n";

            if ($driver === 'mysql') {
                try {
                    $createRow = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE `$table`");
                    if (!empty($createRow)) {
                        $createSql = $createRow[0]->{'Create Table'} ?? null;
                        if ($createSql) {
                            $sqlContent .= "DROP TABLE IF EXISTS `$table`;\n";
                            $sqlContent .= $createSql . ";\n\n";
                        }
                    }
                } catch (\Exception $e) {
                    // Fallback
                }
            }

            $rows = \Illuminate\Support\Facades\DB::table($table)->get();

            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_keys($rowArray);
                    $escapedColumns = array_map(fn($col) => "`$col`", $columns);

                    $escapedValues = [];
                    foreach ($rowArray as $value) {
                        if (is_null($value)) {
                            $escapedValues[] = 'NULL';
                        } elseif (is_numeric($value)) {
                            $escapedValues[] = $value;
                        } else {
                            $escapedValues[] = $pdo->quote((string) $value);
                        }
                    }

                    $sqlContent .= "INSERT INTO `$table` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlContent .= "\n";
            }
        }

        $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filename = 'UTBK_Tracker_Backup_' . date('Y-m-d_H-i-s') . '.sql';

        return Response::make($sqlContent, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
