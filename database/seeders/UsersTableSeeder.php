<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample users for testing
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now()->subDays(45),
                'updated_at' => now()->subDays(45),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now()->subDays(20),
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => null, // Unverified user
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now()->subDays(5),
                'created_at' => now()->subDays(15), // Recent user
                'updated_at' => now()->subDays(5),
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => null, // Unverified recent user
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name' => 'Diana Evans',
                'email' => 'diana@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now()->subDays(2),
                'created_at' => now()->subDays(5), // Recent verified user
                'updated_at' => now()->subDays(2),
            ],
        ];

        foreach ($users as $userData) {
            // Only create if user doesn't exist
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
            }
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('Users created:');
        $this->command->info('- john@example.com (Verified, Old)');
        $this->command->info('- jane@example.com (Verified, Regular)');
        $this->command->info('- bob@example.com (Unverified, Regular)');
        $this->command->info('- alice@example.com (Verified, Recent)');
        $this->command->info('- charlie@example.com (Unverified, Recent)');
        $this->command->info('- diana@example.com (Verified, Recent)');
        $this->command->info('All passwords are: password123');
    }
}
