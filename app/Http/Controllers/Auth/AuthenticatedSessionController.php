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
     * Display the general login view (redirects directly to pelapor login).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('pelapor.login');
    }

    /**
     * Display the Admin & Teknisi login view.
     */
    public function createAdmin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'admin' || $role === 'teknisi') {
                return redirect()->route('admin.dashboard');
            }
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('auth.admin-login');
    }

    /**
     * Handle incoming Admin/Teknisi authentication request.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Username atau email administrator wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->email);
        $lowerInput = strtolower($loginInput);

        $user = User::where(function ($query) use ($loginInput, $lowerInput) {
            $query->where('email', $loginInput)
                ->orWhere('name', $loginInput)
                ->orWhereRaw('LOWER(email) = ?', [$lowerInput])
                ->orWhereRaw('LOWER(name) = ?', [$lowerInput]);

            if (!str_contains($loginInput, '@')) {
                $query->orWhere('email', $loginInput . '@cirebonkab.go.id')
                    ->orWhere('email', $loginInput . '@gmail.com')
                    ->orWhereRaw("SUBSTRING_INDEX(email, '@', 1) = ?", [$loginInput])
                    ->orWhereRaw("LOWER(SUBSTRING_INDEX(email, '@', 1)) = ?", [$lowerInput]);
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
                $query->orWhere('username', $loginInput)
                    ->orWhereRaw('LOWER(username) = ?', [$lowerInput]);
            }
        })->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('Kredensial administrator tidak ditemukan dalam sistem.'),
            ]);
        }

        $isHash = is_string($user->password) && str_starts_with($user->password, '$2y$');
        $isPasswordMatch = $isHash
            ? Hash::check($request->password, $user->password)
            : hash_equals((string) $user->password, $request->password);

        if (!$isPasswordMatch) {
            throw ValidationException::withMessages([
                'email' => __('Username/email atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        // Pastikan hanya role admin atau teknisi yang diizinkan lewat portal admin
        if (!in_array($user->role, ['admin', 'teknisi'])) {
            throw ValidationException::withMessages([
                'email' => __('Akun Anda terdaftar sebagai Pelapor, bukan Administrator atau Teknisi. Silakan gunakan Portal Pelapor.'),
            ]);
        }

        Auth::login($user, false);
        $request->session()->regenerate();
        session()->flash('just_logged_in', $user->id);

        Log::info('Admin/Teknisi login via /admin/login: ' . $user->email . ' (Role: ' . $user->role . ')');

        return redirect()->route('admin.dashboard', ['auth_init' => 1]);
    }

    /**
     * Display the Pelapor / Masyarakat login view.
     */
    public function createPelapor(): View|RedirectResponse
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'admin' || $role === 'teknisi') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('pelapor.dashboard');
        }

        return view('auth.pelapor-login');
    }

    /**
     * Handle incoming Pelapor authentication request.
     */
    public function storePelapor(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Username atau Gmail pelapor wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->email);
        $lowerInput = strtolower($loginInput);

        // Fleksibel: Bisa login menggunakan Username (name) atau Gmail/Email
        $user = User::where(function ($query) use ($loginInput, $lowerInput) {
            $query->where('email', $loginInput)
                ->orWhere('name', $loginInput)
                ->orWhereRaw('LOWER(email) = ?', [$lowerInput])
                ->orWhereRaw('LOWER(name) = ?', [$lowerInput]);

            // Jika user hanya mengetik username tanpa domain email (misal: "pelapor" atau "nifary")
            if (!str_contains($loginInput, '@')) {
                $query->orWhere('email', $loginInput . '@gmail.com')
                    ->orWhereRaw('LOWER(email) = ?', [$lowerInput . '@gmail.com'])
                    ->orWhereRaw("SUBSTRING_INDEX(email, '@', 1) = ?", [$loginInput])
                    ->orWhereRaw("LOWER(SUBSTRING_INDEX(email, '@', 1)) = ?", [$lowerInput]);
            }

            // Jika ada kolom username di tabel users
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
                $query->orWhere('username', $loginInput)
                    ->orWhereRaw('LOWER(username) = ?', [$lowerInput]);
            }
        })->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('Kredensial tidak ditemukan. Pastikan Anda telah memasukkan Username atau Gmail yang terdaftar.'),
            ]);
        }

        $isHash = is_string($user->password) && str_starts_with($user->password, '$2y$');
        $isPasswordMatch = $isHash
            ? Hash::check($request->password, $user->password)
            : hash_equals((string) $user->password, $request->password);

        if (!$isPasswordMatch) {
            throw ValidationException::withMessages([
                'email' => __('Username/Gmail atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        // Portal login web ini khusus untuk Pelapor / Warga. Admin dan Teknisi harus melalui /admin/login
        if (in_array($user->role, ['admin', 'teknisi'])) {
            throw ValidationException::withMessages([
                'email' => __('Portal login ini khusus untuk Pelapor / Warga. Akun Anda terdaftar sebagai Administrator/Teknisi. Silakan masuk melalui Portal Masuk Admin di /admin/login.'),
            ]);
        }

        Auth::login($user, false);
        $request->session()->regenerate();
        session()->flash('just_logged_in', $user->id);

        Log::info('Pelapor login via web login: ' . $user->email . ' (Role: ' . $user->role . ')');

        return redirect()->route('pelapor.dashboard', ['auth_init' => 1]);
    }

    /**
     * Handle general authentication request (fallback to pelapor login).
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->storePelapor($request);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $role = Auth::user()?->role;

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin' || $role === 'teknisi') {
            return redirect()->route('admin.login');
        }

        return redirect()->route('pelapor.login');
    }
}