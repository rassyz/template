<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@winnicode.com'],
            [
                'name' => 'Admin Winnicode',
                'email_verified_at' => now(),
            ]
        );

        // Pastikan hanya assign role jika belum ada
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}
