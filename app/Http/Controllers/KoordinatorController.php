<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Counselor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KoordinatorController extends Controller
{
    // HAPUS __construct() yang bermasalah!
    // Middleware sudah ditangani di routes/web.php
    
    // Dashboard Koordinator
    public function dashboard()
    {
        try {
            // Stats dasar
            $stats = [
                'total_siswa' => DB::table('users')->where('role', 'siswa')->count(),
                'total_guru' => DB::table('users')
                    ->whereIn('role', ['guru_bk', 'guru'])
                    ->count(),
                'konseling_bulan_ini' => DB::table('janji_konselings')
                    ->whereMonth('tanggal', now()->month)
                    ->count(),
                'menunggu_konfirmasi' => DB::table('janji_konselings')
                    ->where('status', 'menunggu')
                    ->count(),
            ];

            // Data jenis konseling untuk chart
            $jenisKonselingRaw = DB::table('janji_konselings')
                ->select('jenis_bimbingan', DB::raw('count(*) as total'))
                ->groupBy('jenis_bimbingan')
                ->get();
            
            $totalAll = $jenisKonselingRaw->sum('total');
            
            $jenisKonselingData = $jenisKonselingRaw->map(function($item) use ($totalAll) {
                $colors = [
                    'pribadi' => ['bg' => 'blue', 'label' => 'Bimbingan Pribadi'],
                    'belajar' => ['bg' => 'green', 'label' => 'Bimbingan Belajar'],
                    'karir' => ['bg' => 'purple', 'label' => 'Bimbingan Karir'],
                    'sosial' => ['bg' => 'orange', 'label' => 'Bimbingan Sosial']
                ];
                
                $percentage = $totalAll > 0 ? ($item->total / $totalAll) * 100 : 0;
                
                return [
                    'jenis' => $item->jenis_bimbingan,
                    'total' => $item->total,
                    'percentage' => round($percentage, 1),
                    'color' => $colors[$item->jenis_bimbingan] ?? ['bg' => 'gray', 'label' => ucfirst($item->jenis_bimbingan)]
                ];
            });

            // Recent activities
            $recentActivities = DB::table('janji_konselings')
                ->join('users', 'janji_konselings.user_id', '=', 'users.id')
                ->select(
                    'janji_konselings.*', 
                    'users.name',
                    'users.email'
                )
                ->orderBy('janji_konselings.created_at', 'desc')
                ->limit(10)
                ->get();

            Log::info('Koordinator dashboard accessed', [
                'user_id' => auth()->id(),
                'stats' => $stats
            ]);

            return view('koordinator.dashboard', compact(
                'stats', 
                'jenisKonselingData', 
                'recentActivities'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in koordinator dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
    }

    // List semua guru BK
    public function indexGuru()
    {
        try {
            $gurus = Counselor::with('user')->paginate(10);
            
            return view('koordinator.guru.index', compact('gurus'));
        } catch (\Exception $e) {
            Log::error('Error in indexGuru', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal memuat data guru BK');
        }
    }

    // Form tambah guru BK
    public function createGuru()
    {
        return view('koordinator.guru.create');
    }

    // Simpan guru BK baru
    public function storeGuru(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'phone' => 'required|string|unique:users',
                'nip' => 'required|string|unique:counselors',
                'password' => 'required|min:8|confirmed',
                'specialization' => 'nullable|string',
                'office_hours' => 'nullable|string',
            ]);

            // Buat user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru_bk',
                'email_verified_at' => now(),
            ]);

            // Buat counselor
            Counselor::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'specialization' => $validated['specialization'],
                'office_hours' => $validated['office_hours'],
            ]);

            Log::info('New guru BK created', [
                'user_id' => $user->id,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('koordinator.guru.index')
                ->with('success', 'Guru BK berhasil ditambahkan');
                
        } catch (\Exception $e) {
            Log::error('Error creating guru BK', [
                'error' => $e->getMessage()
            ]);
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan guru BK: ' . $e->getMessage());
        }
    }

    // Form edit guru BK
    public function editGuru($id)
    {
        try {
            $guru = Counselor::findOrFail($id);
            return view('koordinator.guru.edit', compact('guru'));
        } catch (\Exception $e) {
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }

    // Update guru BK
    public function updateGuru(Request $request, $id)
    {
        try {
            $guru = Counselor::findOrFail($id);
            $user = $guru->user;

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|string|unique:users,phone,' . $user->id,
                'nip' => 'required|string|unique:counselors,nip,' . $guru->id,
                'specialization' => 'nullable|string',
                'office_hours' => 'nullable|string',
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Update user
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update counselor
            $guru->update([
                'nip' => $validated['nip'],
                'specialization' => $validated['specialization'],
                'office_hours' => $validated['office_hours'],
            ]);

            Log::info('Guru BK updated', [
                'guru_id' => $guru->id,
                'updated_by' => auth()->id()
            ]);

            return redirect()->route('koordinator.guru.index')
                ->with('success', 'Guru BK berhasil diupdate');
                
        } catch (\Exception $e) {
            Log::error('Error updating guru BK', [
                'error' => $e->getMessage()
            ]);
            
            return back()->withInput()
                ->with('error', 'Gagal mengupdate guru BK: ' . $e->getMessage());
        }
    }

    // Hapus guru BK
    public function destroyGuru($id)
    {
        try {
            $guru = Counselor::findOrFail($id);
            $user = $guru->user;

            $guru->delete();
            $user->delete();

            Log::info('Guru BK deleted', [
                'guru_id' => $id,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->route('koordinator.guru.index')
                ->with('success', 'Guru BK berhasil dihapus');
                
        } catch (\Exception $e) {
            Log::error('Error deleting guru BK', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal menghapus guru BK');
        }
    }

    // Lihat detail guru BK
    public function showGuru($id)
    {
        try {
            $guru = Counselor::with('user', 'counselingSessions')->findOrFail($id);
            
            return view('koordinator.guru.show', compact('guru'));
        } catch (\Exception $e) {
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }
}