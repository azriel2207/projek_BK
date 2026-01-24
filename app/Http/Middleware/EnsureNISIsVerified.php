<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class EnsureNISIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user adalah siswa dan belum verifikasi NIS, redirect ke halaman verifikasi NIS
        if (Auth::check() && Auth::user()->role === 'siswa') {
            $student = Student::where('user_id', Auth::id())->first();
            
            // Jika belum verifikasi NIS, redirect ke halaman verifikasi
            if (!$student || !$student->nis_verified) {
                return redirect()->route('verification.nis')
                    ->with('warning', 'Silakan verifikasi NIS Anda terlebih dahulu untuk melanjutkan.');
            }
        }

        return $next($request);
    }
}
