<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Administrator Sistem (Admin & Teknisi Menyatu)
        User::updateOrCreate(
            ['email' => 'admin@cirebonkab.go.id'],
            [
                'name' => 'Administrator SIPERKOM',
                'password' => 'cirebon2',
                'role' => 'admin',
                'alamat' => 'Jl. Sunan Kalijaga No. 7, Sumber',
                'avatar' => 'avatars/admin-avatar.svg',
                'must_change_password' => false,
            ]
        );
    }
}