<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PelaporUserSeeder extends Seeder
{
    public function run(): void
    {
        // [START_PELAPOR_SEEDERS]
        // Akun Pelapor: nifary
        User::updateOrCreate(
            ['email' => 'nifary@gmail.com'],
            [
                'name' => 'nifary',
                'password' => '1',
                'role' => 'pelapor',
                'alamat' => 'cirebon',
                'avatar' => null,
                'must_change_password' => false,
            ]
        );
        // [END_PELAPOR_SEEDERS]
    }
}
