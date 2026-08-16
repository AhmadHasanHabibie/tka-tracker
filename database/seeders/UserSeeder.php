<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primary Administrator account
        User::updateOrCreate(
            ['username' => 'Query1234'],
            [
                'name' => 'Administrator UTBK',
                'email' => 'admin@utbktracker.local',
                'role' => 'admin',
                'password' => Hash::make('C798g107'),
            ]
        );

        // Student accounts
        User::updateOrCreate(
            ['username' => 'pejuangutbk'],
            [
                'name' => 'Pejuang UTBK',
                'email' => 'pejuang@utbktracker.local',
                'role' => 'siswa',
                'password' => Hash::make('utbk2026'),
            ]
        );

        User::updateOrCreate(
            ['username' => '11244'],
            [
                'name' => 'User 11244',
                'email' => '11244@utbktracker.local',
                'role' => 'siswa',
                'password' => Hash::make('11244'),
            ]
        );
    }
}
