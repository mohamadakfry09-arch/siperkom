<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset request by username/email and new password, then auto login.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], [
            'username.required' => 'Nama pengguna (username) atau email wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min'      => 'Kata sandi minimal 4 karakter.',
            'password.confirmed'=> 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $username = trim($request->username);
        $user = \App\Models\User::where('name', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Akun dengan username atau email tersebut tidak ditemukan dalam sistem.',
            ]);
        }

        // Simpan sandi baru langsung agar tersinkron dan kebaca di admin manajemen
        $user->password = $request->password;
        $user->must_change_password = 0;
        $user->save();

        // Langsung login otomatis ke sesi pengguna
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect sesuai role dengan pemberitahuan sukses
        if ($user->role === 'admin' || $user->role === 'teknisi') {
            return redirect()->route('admin.dashboard')->with('success', 'Kata sandi berhasil diperbarui! Anda telah masuk secara otomatis.');
        }

        return redirect()->route('pelapor.dashboard')->with('success', 'Kata sandi berhasil diperbarui! Anda telah masuk secara otomatis.');
    }
}
