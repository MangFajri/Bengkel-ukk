<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Daftar peran yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data pengguna yang sedang login
        $user = Auth::user();

        // 3. Lakukan perulangan untuk setiap peran yang diizinkan untuk rute ini
        foreach ($roles as $role) {
            // 4. Periksa apakah peran pengguna cocok dengan salah satu peran yang diizinkan
            if ($user->role == $role) {
                // Jika cocok, izinkan permintaan untuk melanjutkan
                return $next($request);
            }
        }

        // 5. Jika setelah dicek semua tidak ada yang cocok, tolak akses
        abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI HAK AKSES.');
    }
}