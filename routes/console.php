<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Laporan;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Perintah otomatis untuk menghapus laporan yang sudah berumur lebih dari 1 bulan
Artisan::command('reports:delete-old', function () {
    $oneMonthAgo = Carbon::now()->subMonth();
    $deleted = Laporan::where('created_at', '<', $oneMonthAgo)->delete();
    $this->info("Berhasil menghapus {$deleted} laporan lama yang sudah lebih dari 1 bulan.");
})->purpose('Menghapus laporan yang sudah berumur lebih dari 1 bulan');

// Jadwal agar berjalan otomatis setiap hari
Schedule::command('reports:delete-old')->daily();