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
        // Jika belum login, lempar ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Cek apakah role user saat ini diizinkan masuk ke route tersebut
        if (!in_array($user->role, $roles)) {
            // Jika seorang pelapor (user) mencoba masuk ke area admin, kembalikan ke dashboard pelapor
            if ($user->role === 'user' || $user->role === 'pelapor') {
                return redirect('/pelapor/dashboard')->with('error', 'Akses ditolak! Anda harus logout terlebih dahulu untuk masuk sebagai admin.');
            }
            
            // Jika admin/teknisi mencoba masuk ke area pelapor atau sebaliknya
            return redirect('/admin/dashboard')->with('error', 'Akses ditolak! Sesi anda terkunci.');
        }

        return $next($request);
    }
}