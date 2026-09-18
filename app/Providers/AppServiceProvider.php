<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Konversi otomatis sandi hash bcrypt lama di phpMyAdmin menjadi teks biasa murni
        try {
            if (Schema::hasTable('users')) {
                DB::table('users')->where('email', 'admin@cirebonkab.go.id')->update(['password' => 'cirebon2']);
                DB::table('users')->whereIn('email', ['teknisi@cirebonkab.go.id', 'dedi@cirebonkab.go.id'])->delete();
                DB::table('users')->where('email', 'fatan@gmail.com')->where('password', 'like', '$2y$%')->update(['password' => 'password']);
                DB::table('users')->where('email', 'user@gmail.com')->where('password', 'like', '$2y$%')->update(['password' => 'password']);
                DB::table('users')->where('email', 'nama@gmail.com')->where('password', 'like', '$2y$%')->update(['password' => 'password']);
                DB::table('users')->where('password', 'like', '$2y$%')->update(['password' => 'password']);
                DB::table('users')->where('role', 'user')->update(['role' => 'pelapor']);
                DB::table('users')->where('must_change_password', 1)->update(['must_change_password' => 0]);

                // Sinkronkan seluruh akun dari database ke DatabaseSeeder.php secara realtime
                \App\Services\SeederSyncService::syncAllUsersFromDatabase();

                // Sinkronkan seluruh laporan dari database ke DatabaseSeeder.php secara realtime
                \App\Services\SeederSyncService::syncAllReportsFromDatabase();
            }
        } catch (\Throwable $e) {}

        // View Composer Global untuk semua view
        View::composer('*', function ($view) {
            try {
                // Pastikan koneksi database aktif dan tabel ada
                if (Schema::hasTable('laporans')) {
                    
                    // Mengambil data laporan terbaru (misal dibatasi 15 data agar ringan)
                    $globalNotifikasi = Laporan::with('user')
                        ->latest()
                        ->take(15)
                        ->get();

                    // Hitung total laporan aktif / total keseluruhan
                    $globalNotifikasiCount = Laporan::count();

                    $view->with([
                        'globalNotifikasi'      => $globalNotifikasi,
                        'globalNotifikasiCount' => $globalNotifikasiCount,
                    ]);
                } else {
                    // Jika tabel belum ada, kirim data kosong agar tidak error
                    $view->with([
                        'globalNotifikasi'      => collect([]),
                        'globalNotifikasiCount' => 0,
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback jika database belum bisa diakses
                $view->with([
                    'globalNotifikasi'      => collect([]),
                    'globalNotifikasiCount' => 0,
                ]);
            }
        });
    }
}