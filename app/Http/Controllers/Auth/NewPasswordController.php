<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Token reset kata sandi tidak valid atau telah kedaluwarsa.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email yang dimasukkan tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                    'must_change_password' => 0,
                ])->save();

                // Sinkronisasi otomatis ke DatabaseSeeder
                \App\Services\SeederSyncService::syncUserToSeeder($user, $request->password);

                event(new PasswordReset($user));
            }
        );

        // Jika berhasil reset sandi, langsung login otomatis dan arahkan ke dashboard
        // agar tidak mental/terlempar kembali ke halaman login.
        if ($status == Password::PASSWORD_RESET) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                Auth::login($user, false);
                $request->session()->regenerate();
                session()->flash('just_logged_in', $user->id);

                $successMsg = 'Kata sandi berhasil diperbarui! Anda telah masuk secara otomatis.';

                if ($user->role === 'admin' || $user->role === 'teknisi') {
                    return redirect()->route('admin.dashboard', ['auth_init' => 1])
                        ->with('success', $successMsg)
                        ->with('status', $successMsg);
                }

                return redirect()->route('pelapor.dashboard', ['auth_init' => 1])
                    ->with('success', $successMsg)
                    ->with('status', $successMsg);
            }

            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}

