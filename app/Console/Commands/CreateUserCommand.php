<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {username} {password} {--name=Student}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user account for UTBK Tracker manually';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');
        $name = $this->option('name');

        if (User::where('username', $username)->exists()) {
            $this->error("Username '{$username}' already exists.");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'username' => strtolower(trim($username)),
            'email' => strtolower(trim($username)) . '@utbktracker.local',
            'password' => Hash::make($password),
        ]);

        $this->info("User '{$user->username}' created successfully!");
        return 0;
    }
}
