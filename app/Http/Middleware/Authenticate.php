<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = auth()->user()->role;
        
        // PERBAIKAN: Tambahkan mapping role alternatif
        $roleMapping = [
            'koordinator_bk' => ['koordinator_bk', 'koordinator'],
            'koordinator' => ['koordinator_bk', 'koordinator'],
            'guru_bk' => ['guru_bk', 'guru'],
            'guru' => ['guru_bk', 'guru'],
            'siswa' => ['siswa']
        ];
        
        // Dapatkan semua role yang valid untuk user
        $validRoles = $roleMapping[$userRole] ?? [$userRole];
        
        // Cek apakah salah satu role user cocok dengan yang dibutuhkan
        $hasAccess = false;
        foreach ($roles as $role) {
            $allowedRoles = $roleMapping[$role] ?? [$role];
            if (!empty(array_intersect($validRoles, $allowedRoles))) {
                $hasAccess = true;
                break;
            }
        }
        
        if (!$hasAccess) {
            if ($request->expectsJson()) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return redirect('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}