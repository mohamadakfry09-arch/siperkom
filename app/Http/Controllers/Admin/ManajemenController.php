<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManajemenController extends Controller
{
    /**
     * Menampilkan halaman manajemen akun pelapor beserta daftar akun terdaftar.
     */
    public function index()
    {
        // Mengambil semua data user dari database
        $users = User::all();

        return view('admin.manajemen', compact('users'));
    }

    /**
     * Menyimpan akun baru dengan password tersimpan jelas (tanpa hash/acak).
     */
    public function store(Request $request)
    {
        // Validasi input form pendaftaran akun
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'nama_desa' => 'nullable|string',
            'kecamatan' => 'nullable|string',
        ], [
            'name.required' => 'Nama lengkap atau instansi wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email yang dimasukkan tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan alamat email lain.',
            'password.required' => 'Kata sandi awal wajib diisi.',
        ]);

        // Menyimpan data ke tabel users tanpa Hash::make()
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Password tersimpan jelas sesuai ketikan
            'nama_desa' => $request->nama_desa ?? '-',
            'kecamatan' => $request->kecamatan ?? '-',
            'role' => 'pelapor',
        ]);

        return redirect()->back()->with('success', 'Akun pelapor berhasil ditambahkan dengan sandi yang jelas!');
    }

    /**
     * Mereset sandi akun menjadi default ('password123') secara jelas.
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        // Mereset password menjadi teks biasa yang mudah diingat
        $user->password = 'password123';
        $user->save();

        return redirect()->back()->with('success', 'Sandi akun ' . $user->name . ' berhasil direset menjadi: password123');
    }

    /**
     * Menghapus akun pelapor.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus dari sistem.');
    }
}