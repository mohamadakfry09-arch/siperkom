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
        $lower = strtolower($username);
        $user = \App\Models\User::where(function ($query) use ($username, $lower) {
            $query->where('name', $username)
                ->orWhere('email', $username)
                ->orWhereRaw('LOWER(name) = ?', [$lower])
                ->orWhereRaw('LOWER(email) = ?', [$lower]);

            if (!str_contains($username, '@')) {
                $query->orWhere('email', $username . '@gmail.com')
                    ->orWhereRaw('LOWER(email) = ?', [$lower . '@gmail.com'])
                    ->orWhereRaw("SUBSTRING_INDEX(email, '@', 1) = ?", [$username])
                    ->orWhereRaw("LOWER(SUBSTRING_INDEX(email, '@', 1)) = ?", [$lower]);
            }
        })->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Akun dengan username atau email tersebut tidak ditemukan dalam sistem.',
            ]);
        }

        // Simpan sandi baru langsung agar tersinkron dan kebaca di admin manajemen
        $user->password = $request->password;
        $user->must_change_password = 0;
        $user->save();

        // Sinkronisasi otomatis ke DatabaseSeeder
        \App\Services\SeederSyncService::syncUserToSeeder($user, $request->password);

        // Langsung login otomatis ke sesi pengguna
        Auth::login($user, false);
        $request->session()->regenerate();
        session()->flash('just_logged_in', $user->id);

        $successMsg = 'Kata sandi berhasil diperbarui! Anda telah masuk secara otomatis.';

        // Redirect sesuai role dengan pemberitahuan sukses & auth_init agar tidak terlempar ke login
        if ($user->role === 'admin' || $user->role === 'teknisi') {
            return redirect()->route('admin.dashboard', ['auth_init' => 1])
                ->with('success', $successMsg)
                ->with('status', $successMsg);
        }

        return redirect()->route('pelapor.dashboard', ['auth_init' => 1])
            ->with('success', $successMsg)
            ->with('status', $successMsg);
    }
}
