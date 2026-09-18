<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email yang dimasukkan tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $alamat = $request->alamat ?? '-';

        // Pastikan kolom alamat tersedia di tabel users
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'alamat')) {
            \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('alamat')->nullable()->after('email');
            });
        }

        // Menyimpan user baru ke database dengan password teks biasa
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $alamat,
            'password' => $request->password, // Teks langsung tanpa bcrypt
            'role' => 'pelapor',
        ]);

        // Otomatis masukkan akun pelapor baru ke dalam berkas codingan DatabaseSeeder.php
        \App\Services\SeederSyncService::syncUserToSeeder($user, $request->password);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('pelapor.dashboard', absolute: false));
    }
}