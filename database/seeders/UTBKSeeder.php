<?php

namespace Database\Seeders;

use App\Models\UTBKTryout;
use App\Models\UTBKSubtestScore;
use App\Models\User;
use Illuminate\Database\Seeder;

class UTBKSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $to1 = UTBKTryout::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'Tryout Zenius #1'],
                [
                    'date' => '2026-08-16',
                    'overall_score' => 614.57,
                    'notes' => 'Tryout pertama untuk pemetaan nilai dasar UTBK.',
                ]
            );

            $to1Subtests = [
                'penalaran_umum' => 800,
                'ppu' => 435,
                'pbm' => 671,
                'pk' => 478,
                'lbi' => 975,
                'lbe' => 440,
                'penalaran_matematis' => 503,
            ];

            foreach ($to1Subtests as $subtestKey => $score) {
                UTBKSubtestScore::firstOrCreate([
                    'utbk_tryout_id' => $to1->id,
                    'subtest' => $subtestKey,
                ], [
                    'score' => $score,
                ]);
            }

            $to2 = UTBKTryout::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'Tryout Zenius #2'],
                [
                    'date' => '2026-08-20',
                    'overall_score' => 640.20,
                    'notes' => 'Peningkatan pada PPU & PK.',
                ]
            );

            $to2Subtests = [
                'penalaran_umum' => 820,
                'ppu' => 500,
                'pbm' => 690,
                'pk' => 530,
                'lbi' => 950,
                'lbe' => 510,
                'penalaran_matematis' => 590,
            ];

            foreach ($to2Subtests as $subtestKey => $score) {
                UTBKSubtestScore::firstOrCreate([
                    'utbk_tryout_id' => $to2->id,
                    'subtest' => $subtestKey,
                ], [
                    'score' => $score,
                ]);
            }
        }
    }
}
