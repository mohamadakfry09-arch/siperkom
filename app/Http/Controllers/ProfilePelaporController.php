<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Report;
use App\Services\SeederSyncService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
use Exception;

/**
 * Class ProfilePelaporController
 * 
 * Controller ini bertanggung jawab penuh untuk mengelola manajemen profil,
 * pembaruan kredensial akun, pengunggahan foto avatar, serta pengaturan
 * informasi instansi atau desa bagi pengguna dengan peran sebagai pelapor
 * di dalam sistem SIPERKOM Kabupaten Cirebon.
 * 
 * @package App\Http\Controllers
 * @author Nifary Yunus Rahman
 * @version 1.2.4
 */
class ProfilePelaporController extends Controller
{
    /**
     * Konstruktor kelas untuk menetapkan middleware autentikasi dasar.
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Menampilkan halaman formulir penyuntingan profil pelapor desa.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('Akses halaman profil pelapor gagal karena sesi pengguna tidak ditemukan.');
                abort(401, 'Sesi pengguna tidak valid atau telah kedaluwarsa.');
            }

            // Pastikan kolom name_changed_at tersedia di tabel users
            if (!Schema::hasColumn('users', 'name_changed_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('name_changed_at')->nullable()->after('name');
                });
            }

            Log::info('Pengguna dengan ID: ' . $user->id . ' mengakses halaman penyuntingan profil.');

            return view('pelapor.profile.edit', compact('user'));

        } catch (Exception $e) {
            Log::error('Terjadi kesalahan saat memuat halaman profil pelapor: ' . $e->getMessage());
            
            return redirect()->route('pelapor.dashboard')->with('error', 'Terjadi kesalahan sistem saat memuat profil.');
        }
    }

    /**
     * Memperbarui data informasi profil, instansi desa, kecamatan, avatar, 
     * serta keamanan kata sandi pengguna pelapor ke dalam database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
            }

            // Pastikan kolom name_changed_at tersedia di tabel users
            if (!Schema::hasColumn('users', 'name_changed_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('name_changed_at')->nullable()->after('name');
                });
            }

            $validatedData = $request->validate([
                'name'      => ['required', 'string', 'max:255'],
                'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'alamat'    => ['nullable', 'string', 'max:255'],
                'nama_desa' => ['nullable', 'string', 'max:255'],
                'kecamatan' => ['nullable', 'string', 'max:255'],
                'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            ], [
                'name.required'      => 'Nama lengkap wajib diisi.',
                'email.required'     => 'Alamat email wajib diisi.',
                'email.email'        => 'Format alamat email tidak valid.',
                'email.unique'       => 'Alamat email ini sudah digunakan oleh akun lain.',
                'avatar.image'       => 'Berkas yang diunggah harus berupa berkas gambar.',
                'avatar.mimes'       => 'Format gambar avatar harus berformat JPG, JPEG, PNG, atau WEBP.',
                'avatar.max'         => 'Ukuran foto profil (PP) maksimal adalah 2 Megabita (2 MB).',
                'password.min'       => 'Kata sandi baru minimal harus terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            ]);

            // Validasi batas perubahan nama: Maksimal 1 minggu sekali (7 hari cooldown)
            $inputName = trim($validatedData['name']);
            $isNameChanged = ($inputName !== trim($user->name));

            if ($isNameChanged) {
                if (!empty($user->name_changed_at)) {
                    $cooldownDate = Carbon::parse($user->name_changed_at)->addDays(7);
                    if (now()->lt($cooldownDate)) {
                        $diffInMinutes = (int) max(0, now()->diffInMinutes($cooldownDate));
                        $daysLeft = intdiv($diffInMinutes, 1440);
                        $hoursLeft = intdiv($diffInMinutes % 1440, 60);
                        $minutesLeft = $diffInMinutes % 60;

                        if ($daysLeft > 0) {
                            $timeRemaining = $daysLeft . ' hari' . ($hoursLeft > 0 ? ' ' . $hoursLeft . ' jam' : '');
                        } elseif ($hoursLeft > 0) {
                            $timeRemaining = $hoursLeft . ' jam' . ($minutesLeft > 0 ? ' ' . $minutesLeft . ' menit' : '');
                        } else {
                            $timeRemaining = max(1, $minutesLeft) . ' menit';
                        }

                        DB::rollBack();

                        return redirect()
                            ->back()
                            ->withInput()
                            ->withErrors([
                                'name' => 'Nama hanya dapat diubah satu minggu (7 hari) sekali. Anda baru dapat mengganti nama lagi pada ' 
                                          . $cooldownDate->translatedFormat('d F Y, H:i') . ' WIB'
                                          . ' (tersisa ' . $timeRemaining . ').'
                            ]);
                    }
                }

                // Nama disetujui untuk diubah dan update timestamp perubahan
                $user->name = $inputName;
                $user->name_changed_at = now();
                Log::info('Nama pengguna ID: ' . $user->id . ' diubah menjadi: ' . $inputName . '. Cooldown 1 minggu dimulai.');
            }

            // Memperbarui atribut data user lainnya
            $user->email = trim($validatedData['email']);

            if ($request->has('alamat')) {
                $user->alamat = $request->filled('alamat') ? trim($request->input('alamat')) : null;
            }
            
            $rawDesa = $validatedData['nama_desa'] ?? null;
            if ($rawDesa && strtolower(trim($rawDesa)) !== 'belum diatur') {
                $user->nama_desa = trim($rawDesa);
                $user->desa      = trim($rawDesa);
            }
            
            $rawKecamatan = $validatedData['kecamatan'] ?? null;
            if ($rawKecamatan && strtolower(trim($rawKecamatan)) !== 'belum diatur') {
                $user->kecamatan = trim($rawKecamatan);
            }

            if ($request->boolean('delete_avatar') || $request->input('delete_avatar') === '1') {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                    Log::info('Avatar untuk user ID: ' . $user->id . ' berhasil dihapus dari storage.');
                }
                $user->avatar = null;
            } elseif (isset($validatedData['avatar'])) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                    Log::info('Avatar lama untuk user ID: ' . $user->id . ' berhasil dihapus dari storage.');
                }

                $avatarPath = $validatedData['avatar']->store('avatars', 'public');
                $user->avatar = $avatarPath;
                Log::info('Avatar baru berhasil diunggah ke path: ' . $avatarPath);
            }

            // Memperbarui kata sandi dengan teks biasa agar konsisten dengan seeder
            if (!empty($validatedData['password'])) {
                $user->password = $validatedData['password'];
                Log::info('Kata sandi untuk user ID: ' . $user->id . ' telah diperbarui.');
            }

            $user->save();

            DB::commit();

            // Otomatis sinkronkan nama baru atau sandi baru ke codingan PelaporUserSeeder.php
            \App\Services\SeederSyncService::syncAllUsersFromDatabase();

            Log::info('Profil pelapor untuk user ID: ' . $user->id . ' berhasil diperbarui secara keseluruhan.');

            // Jika pengguna memilih simpan dan langsung ingin membuat laporan kerusakan
            if ($request->boolean('save_and_report') || $request->input('action') === 'create_report') {
                return redirect()
                    ->route('pelapor.dashboard', ['open_modal' => 1])
                    ->with('success', 'Profil dan nama pelapor berhasil diperbarui! Silakan isi formulir laporan.');
            }

            // Default kembali ke dashboard pelapor agar nama baru langsung aktif dan siap membuat laporan tanpa mental
            return redirect()
                ->route('pelapor.dashboard')
                ->with('success', 'Profil dan nama pelapor berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui profil pelapor untuk user ID: ' . (Auth::id() ?? 'Tamu') . ' — Pesan Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan sistem saat memperbarui profil: ' . $e->getMessage()]);
        }
    }

    /**
     * Metode tambahan untuk menghapus foto avatar pelapor secara mandiri.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAvatar()
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                Log::info('Berkas avatar fisik berhasil dihapus untuk user ID: ' . $user->id);
            }

            $user->avatar = null;
            $user->save();

            DB::commit();

            return redirect()
                ->route('pelapor.profile.edit')
                ->with('success', 'Foto profil berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus avatar untuk user ID: ' . Auth::id() . ' — Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus foto profil.');
        }
    }

    /**
     * Menghapus akun pelapor secara permanen beserta file avatar terkait.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
            }

            $userId = $user->id;
            $userEmail = $user->email;
            $userName = $user->name;

            // 1. Hapus berkas avatar fisik jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                Log::info('Berkas avatar fisik dihapus saat penghapusan akun untuk user ID: ' . $userId);
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

            // 3. Otomatis hapus blok akun dari berkas codingan DatabaseSeeder.php
            SeederSyncService::removeUserFromSeeder($user);
            SeederSyncService::syncAllReportsFromDatabase();

            // 4. Logout sesi pengguna
            Auth::logout();

            // 5. Hapus akun pengguna dari database
            User::findOrFail($userId)->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('Akun pelapor "' . $userName . '" (ID: ' . $userId . ') berhasil dihapus secara permanen.');

            return Redirect::to('/')->with('success', 'Akun Anda berhasil dihapus secara permanen dari sistem.');

        } catch (Exception $e) {
            Log::error('Gagal menghapus akun pelapor — Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem saat menghapus akun: ' . $e->getMessage());
        }
    }

}