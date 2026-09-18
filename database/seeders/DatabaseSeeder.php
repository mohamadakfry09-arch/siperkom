<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Laporan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sinkronisasi Akun Admin Utama via AdminUserSeeder
        $this->call(AdminUserSeeder::class);

        // 2. Sinkronisasi Akun Pelapor via PelaporUserSeeder (File Terpisah)
        $this->call(PelaporUserSeeder::class);

        // 5. Sinkronisasi Data Laporan (updateOrCreate agar selalu konsisten dan realtime di phpMyAdmin)
        // [START_LAPORAN_SEEDERS]
        // Laporan: #CRB-2026-09-001 - nifary
        Laporan::updateOrCreate(
            ['kode_laporan' => '#CRB-2026-09-001'],
            [
                'user_id' => optional(User::where('email', 'nifary@gmail.com')->first())->id ?? 7,
                'pelapor' => 'nifary',
                'alamat' => 'cirebon',
                'kecamatan' => 'Cirebon',
                'desa' => 'Cirebon',
                'jenis_perangkat' => 'Laptop',
                'perangkat' => 'lenovo',
                'kendala' => 'matot',
                'prioritas' => 'Sedang',
                'status' => 'Selesai',
                'teknisi' => 'Fahri',
                'keterangan_rekap' => 'selesai',
                'foto' => 'laporan/foto/OTymO67az8zfw9HwgWs5ItymF2IpiWRe5DqTE99T.jpg',
                'dokumen' => 'laporan/dokumen/HNX5D5Hl0R9ECbHf0pzpSADG8nWz6XbFf0CPd7wb.pdf',
                'tanggal_keluar' => '2026-09-18',
            ]
        );
        // [END_LAPORAN_SEEDERS]

        if (isset($this->command)) {
            $this->command->info('DatabaseSeeder: Seluruh akun dan data laporan berhasil disinkronkan!');
        }
    }
}