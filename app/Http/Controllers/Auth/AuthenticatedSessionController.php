<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Jika ada sesi yang menggantung saat halaman login diakses, bersihkan agar selalu segar
        if (Auth::check()) {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->email);

        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        // Validasi keberadaan user
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('Username/email atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        // Dukung akun lama yang masih menyimpan sandi biasa tanpa memanggil
        // Hash::check pada nilai yang bukan hash.
        $isHash = is_string($user->password) && str_starts_with($user->password, '$2y$');
        $isPasswordMatch = $isHash
            ? Hash::check($request->password, $user->password)
            : hash_equals((string) $user->password, $request->password);

        if (!$isPasswordMatch) {
            throw ValidationException::withMessages([
                'email' => __('Username/email atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        // Jalankan login tanpa persistent remember cookie
        Auth::login($user, false);
        $request->session()->regenerate();
        session()->flash('just_logged_in', $user->id);

        Log::info('User login: ' . $user->email . ' (Username: ' . $user->name . ') dengan role: ' . $user->role);

        // Arahkan dashboard sesuai role dengan parameter auth_init untuk aktivasi sesi tab
        if ($user->role === 'admin' || $user->role === 'teknisi') {
            return redirect()->route('admin.dashboard', ['auth_init' => 1]);
        }

        return redirect()->route('pelapor.dashboard', ['auth_init' => 1]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}