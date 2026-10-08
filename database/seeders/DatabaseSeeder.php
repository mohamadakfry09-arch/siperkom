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
                'user_id' => optional(User::where('email', 'nifary@gmail.com')->first())->id ?? 19,
                'pelapor' => 'nifary',
                'alamat' => 'kuningan',
                'kecamatan' => 'Cirebon',
                'desa' => 'Cirebon',
                'jenis_perangkat' => 'laptop',
                'perangkat' => 'asus',
                'kendala' => 'mati',
                'prioritas' => 'Sedang',
                'status' => 'Selesai',
                'teknisi' => 'Fahry Rizky Prayogi',
                'keterangan_rekap' => 'di ambil',
                'foto' => 'laporan/foto/TzevaASGw8eiUAEyvsrQSbzcv60OGW6haizCyw1g.jpg',
                'dokumen' => 'laporan/dokumen/kMIixMWB8CCXj2eJu809FucNgqRv5SKXkcFHy4vb.pdf',
                'tanggal_keluar' => '2026-09-25',
            ]
        );

        // Laporan: #CRB-2026-09-002 - Ajis
        Laporan::updateOrCreate(
            ['kode_laporan' => '#CRB-2026-09-002'],
            [
                'user_id' => optional(User::where('email', 'ajis@gmail.com')->first())->id ?? 20,
                'pelapor' => 'Ajis',
                'alamat' => 'Cirebon',
                'kecamatan' => 'Cirebon',
                'desa' => 'Cirebon',
                'jenis_perangkat' => 'Printer',
                'perangkat' => 'Epson',
                'kendala' => 'Tinta enggak keluar',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'teknisi' => 'Fahry Rizky Prayogi',
                'keterangan_rekap' => 'udah selesai',
                'foto' => 'laporan/foto/mMkkgA3R7Ptqi8BzTYHZjF51xVbFJu6Hyq2mDMr1.jpg',
                'dokumen' => 'laporan/dokumen/RmMvtK0vbBmxMmF6hFoaYKJbnGFOCwTynQxWWMBP.pdf',
                'tanggal_keluar' => '2026-09-26',
            ]
        );

        // Laporan: #CRB-2026-09-003 - Ajis
        Laporan::updateOrCreate(
            ['kode_laporan' => '#CRB-2026-09-003'],
            [
                'user_id' => optional(User::where('email', 'ajis@gmail.com')->first())->id ?? 20,
                'pelapor' => 'Ajis',
                'alamat' => 'Cirebon',
                'kecamatan' => 'Cirebon',
                'desa' => 'Cirebon',
                'jenis_perangkat' => 'Router',
                'perangkat' => 'Engga tau',
                'kendala' => 'Engga nyala',
                'prioritas' => 'Sedang',
                'status' => 'Ditolak',
                'teknisi' => 'Fahry Rizky Prayogi',
                'keterangan_rekap' => 'Status diperbarui menjadi Menunggu Part',
                'foto' => 'laporan/foto/ZwULNFwZbhb1ysePsJsmjAylm0WBtOkMulT0ysSR.jpg',
                'dokumen' => 'laporan/dokumen/BOmgCkX33AjD2eFMNU8Yzv3Gvs7KtWegQM4dsq1Z.pdf',
                'tanggal_keluar' => '2026-10-02',
            ]
        );

        // Laporan: #CRB-2026-10-001 - nifary
        Laporan::updateOrCreate(
            ['kode_laporan' => '#CRB-2026-10-001'],
            [
                'user_id' => optional(User::where('email', 'nifary@gmail.com')->first())->id ?? 19,
                'pelapor' => 'nifary',
                'alamat' => 'kuningan',
                'kecamatan' => 'Cirebon',
                'desa' => 'Cirebon',
                'jenis_perangkat' => 'hahaha',
                'perangkat' => 'ajis',
                'kendala' => 'lapar',
                'prioritas' => 'Sedang',
                'status' => 'Selesai',
                'teknisi' => 'Fahry Rizky Prayogi',
                'keterangan_rekap' => 'Status diperbarui menjadi Dalam Perbaikan',
                'foto' => 'laporan/foto/R69IvFOeuxopOJpZhweQgPqEBscmDN2JocBfP50q.jpg',
                'dokumen' => 'laporan/dokumen/gtFw07mDXgID68NN0Dj00MaUttoj0RsO8VYcSX3m.pdf',
                'tanggal_keluar' => '2026-10-02',
            ]
        );
        // [END_LAPORAN_SEEDERS]

        if (isset($this->command)) {
            $this->command->info('DatabaseSeeder: Seluruh akun dan data laporan berhasil disinkronkan!');
        }
    }
}