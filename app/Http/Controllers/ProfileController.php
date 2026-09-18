<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

/**
 * Class ProfileController
 * 
 * Mengelola pembaruan profil pengguna, pengaturan wilayah desa/kecamatan,
 * serta penghapusan akun secara permanen dalam sistem SIPERKOM Kabupaten Cirebon.
 * 
 * @package App\Http\Controllers
 * @author Nifary Yunus Rahman
 * @version 1.2.4
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman formulir edit profil pelapor desa.
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('pelapor.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pelapor desa (nama, email, desa, kecamatan, dan avatar)
     * dengan format huruf kapital di setiap awal kata (Title Case).
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil data yang sudah lolos validasi dari ProfileUpdateRequest
        $validatedData = $request->validated();

        // Format teks menjadi Title Case (Huruf Besar di Setiap Kata)
        if (isset($validatedData['name'])) {
            $validatedData['name'] = ucwords(strtolower($validatedData['name']));
        }
        
        if (isset($validatedData['alamat'])) {
            $validatedData['alamat'] = ucwords(strtolower($validatedData['alamat']));
        }

        // Tangani proses unggah file foto profil (avatar) jika ada file baru yang diunggah
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika diperlukan atau langsung simpan file baru ke storage/public/avatars
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        // Isi instansiasi model user dengan data tervalidasi
        $request->user()->fill($validatedData);

        // Jika alamat email diubah oleh pengguna, reset status verifikasi email-nya
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Simpan seluruh perubahan ke dalam database
        $request->user()->save();

        // Redirect kembali ke halaman edit profil dengan pesan sukses
        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Profil berhasil diperbarui dengan sukses.');
    }

    /**
     * Menghapus akun pelapor secara permanen dari sistem SIPERKOM.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if (!$user) {
            return Redirect::route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userId = $user->getAuthIdentifier();

        // Lakukan logout sesi pengguna terlebih dahulu
        Auth::logout();

        // Otomatis hapus blok akun dari berkas codingan seeder
        \App\Services\SeederSyncService::removeUserFromSeeder($user);

        // Hapus akun dari database secara permanen
        User::findOrFail($userId)->delete();

        // Invalidasi sesi dan regenerate token keamanan untuk mencegah CSRF lanjutan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Akun Anda telah berhasil dihapus secara permanen dari sistem.');
    }
}