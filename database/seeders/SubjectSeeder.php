<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'TKA Matematika',
            'TKA Bahasa Indonesia',
            'TKA Bahasa Inggris',
        ];

        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }
    }
}
