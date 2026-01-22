<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'),
        ]);

        // Create demo user (optional)
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@company.com',
            'password' => Hash::make('demo123'),
        ]);

        echo "✅ Users created successfully!\n";
        echo "   Admin: admin@company.com / password123\n";
        echo "   Demo:  demo@company.com / demo123\n";
    }
}
