<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Goal::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'university_name' => 'Universitas Indonesia',
                    'study_program' => 'Ilmu Komputer / Teknik Informatika',
                    'target_score' => 'Target Skor UTBK: 760+ | Bismillah Lulus SNBT!',
                    'photo_path' => null,
                ]
            );
        }
    }
}
