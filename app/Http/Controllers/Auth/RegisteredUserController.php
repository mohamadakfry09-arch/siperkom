<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Validation\Rules\Password;

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
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email yang dimasukkan tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.letters' => 'Kata sandi harus mengandung huruf.',
            'password.mixed' => 'Kata sandi harus mengandung kombinasi huruf besar (kapital) dan huruf kecil.',
            'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
            'password.symbols' => 'Kata sandi harus mengandung setidaknya satu simbol khusus (@, #, $, dll).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai dengan kata sandi yang dimasukkan.',
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
        session()->flash('just_logged_in', $user->id);

        return redirect()->route('pelapor.dashboard', ['auth_init' => 1]);
    }

    /**
     * Display the admin registration view.
     */
    public function createAdmin(): View
    {
        return view('auth.admin-register');
    }

    /**
     * Handle an incoming admin registration request.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama lengkap petugas admin wajib diisi.',
            'email.required' => 'Alamat email resmi wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin',
        ]);

        // Sinkronisasi otomatis ke DatabaseSeeder
        \App\Services\SeederSyncService::syncUserToSeeder($user, $request->password);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard', ['auth_init' => 1]);
    }
}