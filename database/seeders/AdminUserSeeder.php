<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bharatheritage.in'],
            [
                'name' => 'BHA Curator Admin',
                'password' => Hash::make('BharatHeritage@2026'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
