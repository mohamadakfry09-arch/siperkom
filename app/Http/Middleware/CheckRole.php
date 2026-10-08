<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login, lempar ke halaman login yang sesuai
        if (!Auth::check()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('pelapor.login');
        }

        $user = Auth::user();

        // Cek apakah role user saat ini diizinkan masuk ke route tersebut
        if (!in_array($user->role, $roles)) {
            // Jika seorang pelapor (user) mencoba masuk ke area admin, kembalikan ke dashboard pelapor
            if ($user->role === 'user' || $user->role === 'pelapor') {
                return redirect()->route('pelapor.dashboard')->with('error', 'Akses ditolak! Anda masuk sebagai Pelapor.');
            }
            
            // Jika admin/teknisi mencoba masuk ke area pelapor
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak! Sesi Anda adalah Administrator/Teknisi.');
        }

        return $next($request);
    }
}