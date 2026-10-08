<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Report;
use App\Services\SeederSyncService;

class UserController extends Controller
{
    /**
     * Menampilkan daftar akun dan data laporan untuk notifikasi.
     */
    public function index()
    {
        $users = User::whereNotIn('role', ['admin', 'administrator', 'teknisi'])
            ->latest()
            ->get();
        
        // Notifikasi Laporan Masuk untuk Admin (antrean aktif pelapor yang membutuhkan penanganan)
        $notifReports = Laporan::with('user')
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->take(15)
            ->get()
            ->map(function($item) {
                return [
                    'id'            => $item->kode_laporan ?? '#REP-' . $item->id,
                    'db_id'         => $item->id,
                    'pelapor'       => $item->user->name ?? $item->nama_pelapor ?? 'Staf Pelapor',
                    'desa'          => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                    'kecamatan'     => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                    'perangkat'     => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                    'kendala'       => $item->kendala ?? '-',
                    'status'        => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'     => $item->prioritas ?? 'Sedang',
                    'teknisi'       => $item->teknisi ?? 'Fahry Rizky Prayogi',
                ];
            })->values()->toArray();

        $initialUsers = $users->map(fn($u) => [
            'id'       => $u->id,
            'name'     => $u->name,
            'email'    => $u->email,
            'password' => $u->password,
            'alamat'   => $u->alamat ?? '-',
            'avatar'   => $u->avatar ? asset('storage/' . $u->avatar) : null,
            'role'     => $u->role,
        ])->values()->all();

        return view('admin.manajemen', compact('users', 'notifReports', 'initialUsers'));
    }

    /**
     * Live-sync: mengembalikan daftar user pelapor terbaru dalam JSON untuk polling real-time.
     */
    public function liveSync()
    {
        $users = User::whereNotIn('role', ['admin', 'administrator', 'teknisi'])
            ->latest()
            ->get()
            ->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->name,
                'email'    => $u->email,
                'password' => $u->password,
                'alamat'   => $u->alamat ?? '-',
                'avatar'   => $u->avatar ? asset('storage/' . $u->avatar) : null,
                'role'     => $u->role,
            ])
            ->values()
            ->all();

        return response()->json(['users' => $users, 'total' => count($users)]);
    }

    /**
     * Menyimpan akun baru dengan sandi teks biasa (jelas/tanpa hash).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'alamat' => 'nullable|string',
        ]);

        $alamat = $request->alamat ?? '-';

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'alamat' => $alamat,
            'role' => 'pelapor',
            'must_change_password' => false,
        ]);

        // Otomatis masukkan akun pelapor baru ke dalam berkas codingan DatabaseSeeder.php
        SeederSyncService::syncUserToSeeder($newUser, $request->password);

        return redirect()->route('admin.manajemen')->with('success', 'Akun pelapor berhasil ditambahkan dengan sandi yang jelas!');
    }

    /**
     * Menghapus akun pelapor berdasarkan ID secara tuntas dan aman.
     */
    public function destroy(int $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === 'admin') {
                return redirect()->route('admin.manajemen')
                    ->with('error', 'Akun admin tidak dapat dihapus.');
            }

            $userName = $user->name;
            $userEmail = $user->email;
            $userId = $user->id;

            // 1. Hapus berkas avatar fisik jika ada di storage
            if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 2. Hapus data relasi terkait di database agar tidak terkendala foreign key constraint
            if (Schema::hasTable('laporans')) {
                Laporan::where('user_id', $userId)->delete();
            }

            if (Schema::hasTable('reports')) {
                Report::where('user_id', $userId)->delete();
            }

            if (Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $userId)->delete();
            }

            if (Schema::hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->where('email', $userEmail)->delete();
            }

            // 3. Otomatis hapus blok akun dari berkas codingan DatabaseSeeder.php & AdminUserSeeder.php
            SeederSyncService::removeUserFromSeeder($user);
            SeederSyncService::syncAllReportsFromDatabase();

            // 4. Hapus akun pengguna dari database
            $user->delete();

            return redirect()->route('admin.manajemen')
                ->with('success', 'Akun pelapor "' . $userName . '" (' . $userEmail . ') beserta seluruh data terkait dan codingannya berhasil dihapus!');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus akun pelapor di manajemen: ' . $e->getMessage());

            return redirect()->route('admin.manajemen')
                ->with('error', 'Terjadi kesalahan saat menghapus akun pelapor: ' . $e->getMessage());
        }
    }
}