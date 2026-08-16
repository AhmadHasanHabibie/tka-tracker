<?php

namespace Database\Seeders;

use App\Models\StudyXpLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StudyXpLogSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::today()->subDays(1);

        $users = User::all();

        foreach ($users as $user) {
            // Yesterday
            StudyXpLog::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'login',
                'activity_date' => $yesterday->toDateString(),
            ], [
                'description' => 'Login Pertama Hari Ini (+5 XP)',
                'xp' => 5,
            ]);

            StudyXpLog::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'todo_completed',
                'description' => 'Matematika: Penalaran Utama',
                'activity_date' => $yesterday->toDateString(),
            ], [
                'reference_id' => 1,
                'xp' => 10,
            ]);

            StudyXpLog::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'todo_completed',
                'description' => 'Fisika: Gelombang dan Bunyi',
                'activity_date' => $yesterday->toDateString(),
            ], [
                'reference_id' => 2,
                'xp' => 10,
            ]);

            // Today
            StudyXpLog::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'login',
                'activity_date' => $today->toDateString(),
            ], [
                'description' => 'Login Pertama Hari Ini (+5 XP)',
                'xp' => 5,
            ]);

            StudyXpLog::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'todo_completed',
                'description' => 'Bahasa Inggris: Reading Comprehension',
                'activity_date' => $today->toDateString(),
            ], [
                'reference_id' => 3,
                'xp' => 10,
            ]);
        }
    }
}
