<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only seed user accounts and essential subject categories
        // All student user workspaces start completely clean!
        $this->call([
            UserSeeder::class,
            SubjectSeeder::class,
            TKASubjectSeeder::class,
        ]);
    }
}
