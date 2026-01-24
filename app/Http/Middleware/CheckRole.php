<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): RedirectResponse|Response|\Illuminate\Http\JsonResponse
    {
        // Cek jika user tidak login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // Debug log
        Log::info('CheckRole Middleware', [
            'user_id' => $user->id,
            'user_role' => $userRole,
            'required_roles' => $roles,
            'path' => $request->path()
        ]);

        // Role mapping untuk kompatibilitas
        $roleMapping = [
            'koordinator_bk' => ['koordinator_bk', 'koordinator'],
            'koordinator' => ['koordinator_bk', 'koordinator'],
            'guru_bk' => ['guru_bk', 'guru'],
            'guru' => ['guru_bk', 'guru'],
            'siswa' => ['siswa'],
            'wali_kelas' => ['wali_kelas']
        ];

        // Dapatkan semua role yang valid untuk user
        $validUserRoles = $roleMapping[$userRole] ?? [$userRole];

        // Cek apakah user memiliki akses
        $hasAccess = false;
        foreach ($roles as $requiredRole) {
            if (in_array($requiredRole, $validUserRoles)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            Log::warning('Access denied', [
                'user_id' => $user->id,
                'user_role' => $userRole,
                'required_roles' => $roles
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Anda tidak memiliki akses ke resource ini.'
                ], 403);
            }

            return redirect('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}