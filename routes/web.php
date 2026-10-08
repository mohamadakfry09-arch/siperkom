<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// --- CONTROLLER DOKUMENTASI PROFILE & PENGGUNA ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePelaporController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// --- CONTROLLER MODUL LAYANAN PELAPOR / DESA ---
use App\Http\Controllers\Pelapor\LaporanController as PelaporLaporanController;
use App\Http\Controllers\Pelapor\FeedbackController;
use App\Http\Controllers\Pelapor\ProfilDesaController;

// --- CONTROLLER MODUL ADMINISTRATOR & TEKNISI ---
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\Admin\PerangkatController;
use App\Http\Controllers\Admin\LaporanAdminController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\AntreanController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileAdminController;

// --- CONTROLLER FITUR LAYANAN PUBLIK & NOTIFIKASI ---
use App\Http\Controllers\PublicController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Pelaporan Kerusakan Infrastruktur (SIPERKOM)
|--------------------------------------------------------------------------
*/

// 1. HALAMAN LANDING PAGE & PUBLIK
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin' || $role === 'teknisi') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('pelapor.dashboard');
    }
    return view('landing');
});
Route::get('/beranda', function () {
    return view('landing');
})->name('beranda');

// 2. AKSES PUBLIC DOKUMEN / STORAGE SECARA LANGSUNG
Route::get('/storage/{path}', function ($path) {
    $path = str_replace(['../', '..\\'], '', $path); // sanitasi keamanan

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    $fullPath = storage_path('app/public/' . $path);
    $mimeType = mime_content_type($fullPath);

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.file');

// 3. KELOMPOK ROUTE TERPROTEKSI UMUM
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Global Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::get('/admin/notifications', [NotificationController::class, 'getNotifications'])->name('admin.notifications.json'); 
    Route::post('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.readAll');

    Route::get('/dashboard', function () {
        $role = Auth::user()?->role;
        if ($role === 'admin' || $role === 'teknisi') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('pelapor.dashboard');
    })->name('dashboard');

    // Rute Aman Pratinjau Dokumen PDF
    Route::get('/dokumen-preview/{filename}', function ($filename) {
        $filename = basename($filename);
        
        $possiblePaths = [
            'laporan/dokumen/' . $filename,
            'dokumen/' . $filename,
            'laporan/' . $filename,
            $filename
        ];

        $targetPath = null;
        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                $targetPath = $path;
                break;
            }
        }

        if (!$targetPath) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $disposition = request()->has('download') ? 'attachment' : 'inline';

        return response()->file(storage_path('app/public/' . $targetPath), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    })->name('dokumen.preview');

    Route::get('/pelapor/dokumen-preview/{filename}', function ($filename) {
        return redirect()->route('dokumen.preview', ['filename' => $filename]);
    });

    // ==========================================
    // AREA PELAPOR (Khusus User / Pelapor Desa)
    // ==========================================
    Route::middleware(['role:user,pelapor'])->prefix('pelapor')->name('pelapor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/live-sync', [DashboardController::class, 'liveSync'])->name('live-sync');
        
        Route::get('/riwayat', [DashboardController::class, 'riwayat'])->name('riwayat');
        Route::get('/rekap', [DashboardController::class, 'rekap'])->name('rekap');

        Route::get('/profil-desa', [ProfilDesaController::class, 'index'])->name('profil-desa.index');
        Route::put('/profil-desa/update', [ProfilDesaController::class, 'update'])->name('profil-desa.update');
        
        Route::get('/feedback/{laporan_id}', [FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

        Route::get('/profile', [ProfilePelaporController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfilePelaporController::class, 'update'])->name('profile.update');
        Route::delete('/profile/avatar', [ProfilePelaporController::class, 'removeAvatar'])->name('profile.avatar.destroy');
        Route::delete('/profile', [ProfilePelaporController::class, 'destroy'])->name('profile.destroy');

        // Rute Manajemen Laporan
        Route::get('/laporan', [PelaporLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/create', [PelaporLaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [PelaporLaporanController::class, 'store'])->name('laporan.store');
        
        Route::get('/laporan/{id}', [PelaporLaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/{id}/edit', [PelaporLaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{id}', [PelaporLaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{id}/hapus', [PelaporLaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::post('/laporan/{id}/batalkan', [PelaporLaporanController::class, 'batalkan'])->name('laporan.batalkan');

        Route::post('/laporan/{id}/komentar', [PelaporLaporanController::class, 'tambahKomentar'])->name('laporan.komentar');
    });

    // ==========================================
    // AREA ADMIN & TEKNISI
    // ==========================================
    Route::middleware(['role:admin,teknisi'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [LaporanAdminController::class, 'dashboardOverview'])->name('dashboard');
        
        Route::get('/antrean', [AntreanController::class, 'index'])->name('antrean');
        Route::get('/antrean/live-sync', [AntreanController::class, 'liveSync'])->name('antrean.live-sync');
        Route::patch('/laporan/{id}/status', [AntreanController::class, 'updateStatus'])->name('laporan.updateStatus');
        Route::delete('/laporan/{id}', [AntreanController::class, 'destroy'])->name('laporan.destroy');
        
        Route::get('/persentase', [LaporanAdminController::class, 'persentase'])->name('persentase');
        
        Route::get('/rekap', [LaporanAdminController::class, 'rekap'])->name('rekap');
        
        // Rute Baru: Memperbarui keterangan rekap secara interaktif
        Route::patch('/rekap/{id}/keterangan', [LaporanAdminController::class, 'updateKeteranganRekap'])->name('rekap.updateKeterangan');
        
        // Rute Ekspor Excel & PDF yang terhubung langsung ke LaporanAdminController
        Route::get('/export-excel', [LaporanAdminController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export-pdf', [LaporanAdminController::class, 'exportPdf'])->name('export.pdf');
        
        Route::get('/laporan/ekspor/excel', [LaporanAdminController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/ekspor/pdf', [LaporanAdminController::class, 'exportPdf'])->name('laporan.pdf');

        // Rute Manajemen Akun Pelapor
        Route::get('/manajemen', [UserController::class, 'index'])->name('manajemen');
        Route::get('/manajemen/live-sync', [UserController::class, 'liveSync'])->name('manajemen.live-sync');
        Route::post('/manajemen', [UserController::class, 'store'])->name('manajemen.store');
        Route::delete('/manajemen/{id}', [UserController::class, 'destroy'])->name('manajemen.destroy');

        Route::resource('users', UserController::class);
        Route::resource('perangkat', PerangkatController::class);
        Route::resource('teknisi', TeknisiController::class);

        Route::get('/manajemen-laporan', [LaporanAdminController::class, 'index'])->name('manajemen-laporan.index');
        Route::get('/manajemen-laporan/{id}', [LaporanAdminController::class, 'show'])->name('manajemen-laporan.show');

        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

        Route::resource('kategori', KategoriController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
        Route::get('/log-aktivitas/ekspor', [LogAktivitasController::class, 'export'])->name('log-aktivitas.export');

        // Rute Profil Administrator
        Route::get('/profil', [ProfileAdminController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileAdminController::class, 'update'])->name('profil.update');
        Route::delete('/profil/avatar', [ProfileAdminController::class, 'removeAvatar'])->name('profil.avatar.destroy');
        Route::delete('/profil', [ProfileAdminController::class, 'destroy'])->name('profil.destroy');

        // Alias Rute Profile Administrator
        Route::get('/profile', [ProfileAdminController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileAdminController::class, 'update'])->name('profile.update');
        Route::delete('/profile/avatar', [ProfileAdminController::class, 'removeAvatar'])->name('profile.avatar.destroy');
        Route::delete('/profile', [ProfileAdminController::class, 'destroy'])->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';