<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Services\SeederSyncService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
use Exception;

/**
 * Class ProfileAdminController
 * 
 * Mengelola pembaruan profil administrator dan teknisi di SIPERKOM Kabupaten Cirebon,
 * termasuk nama, email, alamat kantor/instansi, foto profil (avatar), dan kata sandi.
 */
class ProfileAdminController extends Controller
{
    /**
     * Menampilkan halaman formulir penyuntingan profil administrator.
     */
    public function edit()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('Akses halaman profil admin gagal: Sesi tidak ditemukan.');
                abort(401, 'Sesi pengguna tidak valid atau telah kedaluwarsa.');
            }

            // Pastikan kolom name_changed_at tersedia di tabel users
            if (!Schema::hasColumn('users', 'name_changed_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('name_changed_at')->nullable()->after('name');
                });
            }

            Log::info('Administrator ID: ' . $user->id . ' mengakses halaman profil admin.');

            return view('admin.profil.edit', compact('user'));

        } catch (Exception $e) {
            Log::error('Terjadi kesalahan saat memuat halaman profil admin: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan sistem saat memuat profil.');
        }
    }

    /**
     * Memperbarui informasi profil administrator (nama, email, alamat instansi, avatar, sandi).
     */
    public function update(Request $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
            }

            if (!Schema::hasColumn('users', 'name_changed_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('name_changed_at')->nullable()->after('name');
                });
            }

            $validatedData = $request->validate([
                'name'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'alamat'        => ['nullable', 'string', 'max:255'],
                'avatar'        => ['nullable'],
                'avatar_base64' => ['nullable', 'string'],
                'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            ], [
                'name.required'      => 'Nama lengkap administrator wajib diisi.',
                'email.required'     => 'Alamat email wajib diisi.',
                'email.email'        => 'Format alamat email tidak valid.',
                'email.unique'       => 'Alamat email ini sudah digunakan oleh akun lain.',
                'password.min'       => 'Kata sandi baru minimal harus terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            ]);

            // Batasan perubahan nama (cooldown 7 hari seperti di pelapor)
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

                $user->name = $inputName;
                $user->name_changed_at = now();
                Log::info('Nama admin ID: ' . $user->id . ' diubah menjadi: ' . $inputName);
            }

            // Perbarui alamat email & alamat instansi
            $user->email = trim($validatedData['email']);

            if ($request->has('alamat')) {
                $user->alamat = $request->filled('alamat') ? trim($request->input('alamat')) : null;
            }

            // Penanganan avatar
            if ($request->boolean('delete_avatar') || $request->input('delete_avatar') === '1') {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                    Log::info('Avatar admin ID: ' . $user->id . ' berhasil dihapus dari storage.');
                }
                $user->avatar = null;
            } elseif ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                    Log::info('Avatar lama admin ID: ' . $user->id . ' dihapus dari storage.');
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
                Log::info('Avatar baru admin berhasil diunggah: ' . $avatarPath);
            } elseif ($request->filled('avatar_base64') && str_starts_with($request->input('avatar_base64'), 'data:image')) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $base64Data = $request->input('avatar_base64');
                $imageParts = explode(';base64,', $base64Data);
                if (count($imageParts) === 2) {
                    $imageTypeAux = explode('image/', $imageParts[0]);
                    $imageType = $imageTypeAux[1] ?? 'jpg';
                    if ($imageType === 'jpeg') $imageType = 'jpg';
                    $imageBase64 = base64_decode($imageParts[1]);
                    $fileName = 'avatars/avatar_admin_' . $user->id . '_' . time() . '.' . $imageType;
                    Storage::disk('public')->put($fileName, $imageBase64);
                    $user->avatar = $fileName;
                    Log::info('Avatar base64 admin berhasil disimpan: ' . $fileName);
                }
            }

            // Perbarui kata sandi jika diisi
            if (!empty($validatedData['password'])) {
                $user->password = $validatedData['password'];
                Log::info('Kata sandi admin ID: ' . $user->id . ' telah diperbarui.');
            }

            $user->save();

            DB::commit();

            // Sinkronkan ke seeder admin jika ada
            if (method_exists(SeederSyncService::class, 'syncAdminToSeeder')) {
                SeederSyncService::syncAdminToSeeder($user);
            }

            Log::info('Profil Administrator ID: ' . $user->id . ' berhasil diperbarui.');

            return redirect()
                ->route('admin.profil.edit')
                ->with('status', 'profile-updated')
                ->with('success', 'Profil Administrator berhasil diperbarui dengan sukses.');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui profil admin: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan sistem saat memperbarui profil: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghapus foto profil avatar administrator.
     */
    public function removeAvatar(): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                Log::info('Avatar admin ID: ' . $user->id . ' berhasil dihapus.');
            }

            $user->avatar = null;
            $user->save();

            DB::commit();

            if (method_exists(SeederSyncService::class, 'syncAdminToSeeder')) {
                SeederSyncService::syncAdminToSeeder($user);
            }

            return redirect()
                ->route('admin.profil.edit')
                ->with('status', 'profile-updated')
                ->with('success', 'Foto profil berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus avatar admin: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus foto profil.');
        }
    }

    /**
     * Menghapus akun administrator secara permanen (dengan proteksi minimal 1 admin).
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
            }

            // Proteksi keamanan: Tidak boleh menghapus jika merupakan admin tunggal
            $adminCount = User::whereIn('role', ['admin'])->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->back()
                    ->with('error', 'Akun Administrator utama tidak dapat dihapus demi keamanan dan kelangsungan operasional sistem SIPERKOM.');
            }

            $userId = $user->id;
            $userName = $user->name;

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            Auth::logout();

            User::findOrFail($userId)->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('Akun administrator "' . $userName . '" (ID: ' . $userId . ') telah dihapus.');

            return redirect()->route('login')->with('success', 'Akun administrator berhasil dihapus dari sistem.');

        } catch (Exception $e) {
            Log::error('Gagal menghapus akun admin: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem saat menghapus akun: ' . $e->getMessage());
        }
    }
}
