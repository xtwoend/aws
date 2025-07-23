<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--force : Force create admin even if exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with login: admin@admin.com, password: admin123';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin@admin.com';
        $existingUser = User::where('email', $email)->first();

        if ($existingUser && !$this->option('force')) {
            $this->error('Admin user already exists! Use --force to overwrite.');
            return 1;
        }

        if ($existingUser && $this->option('force')) {
            $existingUser->delete();
            $this->info('Existing admin user deleted.');
        }

        $user = User::create([
            'name' => 'Administrator',
            'email' => $email,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Email', $user->email],
                ['Password', 'admin123'],
                ['Name', $user->name],
                ['Created', $user->created_at->format('Y-m-d H:i:s')],
            ]
        );

        return 0;
    }
}
