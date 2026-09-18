<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan; // Sesuaikan dengan nama model laporan Anda
use Carbon\Carbon;

class DeleteOldReports extends Command
{
    protected $signature = 'reports:delete-old';
    protected $description = 'Menghapus laporan yang sudah berumur lebih dari 1 bulan';

    public function handle()
    {
        // Menghitung tanggal 1 bulan yang lalu dari hari ini
        $oneMonthAgo = Carbon::now()->subMonth();

        // Hapus laporan yang tanggal dibuatnya (created_at) lebih lama dari 1 bulan
        $deleted = Laporan::where('created_at', '<', $oneMonthAgo)->delete();

        $this->info("Berhasil menghapus {$deleted} laporan lama yang sudah lebih dari 1 bulan.");
    }
}