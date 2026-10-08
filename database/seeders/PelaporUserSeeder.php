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
                'password' => 'Nifary16@',
                'role' => 'pelapor',
                'alamat' => 'kuningan',
                'avatar' => 'avatars/rxgNk4DBf48cPcYgALMOQesLOQtm25IUNlWCk0as.jpg',
                'must_change_password' => false,
            ]
        );

        // Akun Pelapor: Ajis
        User::updateOrCreate(
            ['email' => 'ajis@gmail.com'],
            [
                'name' => 'Ajis',
                'password' => 'Ajis1234@',
                'role' => 'pelapor',
                'alamat' => 'Cirebon',
                'avatar' => null,
                'must_change_password' => false,
            ]
        );
        // [END_PELAPOR_SEEDERS]
    }
}
