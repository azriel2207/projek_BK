<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Counselor;
use App\Models\Student;
use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KoordinatorController extends Controller
{
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

    /**
     * MANAJEMEN GURU BK
     */
   public function indexGuru()
{
    try {
        $gurus = Counselor::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('koordinator.guru.index', compact('gurus'));
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memuat data guru BK');
    }
}

    public function createGuru()
    {
        return view('koordinator.guru.create');
    }

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

            // Buat counselor - SESUAIKAN DENGAN MODEL
            Counselor::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['name'],  // GUNAKAN nama_lengkap
                'nip' => $validated['nip'],
                'no_hp' => $validated['phone'],        // GUNAKAN no_hp
                'specialization' => $validated['specialization'] ?? 'Umum',
                'office_hours' => $validated['office_hours'] ?? '08:00 - 16:00',
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

    public function editGuru($id)
    {
        try {
            $guru = Counselor::findOrFail($id);
            return view('koordinator.guru.edit', compact('guru'));
        } catch (\Exception $e) {
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }

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

            // Update counselor - SESUAIKAN DENGAN MODEL
            $guru->update([
                'nama_lengkap' => $validated['name'],  // GUNAKAN nama_lengkap
                'nip' => $validated['nip'],
                'no_hp' => $validated['phone'],        // GUNAKAN no_hp
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

    public function showGuru($id)
    {
        try {
            $guru = Counselor::with('user')->findOrFail($id);
            
            return view('koordinator.guru.show', compact('guru'));
        } catch (\Exception $e) {
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }

    /**
 * MANAJEMEN SISWA
 */
public function indexSiswa()
{
    try {
        $siswas = Student::with(['user' => function($query) {
            $query->select('id', 'name', 'email');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        Log::info('Data siswa loaded', [
            'total' => $siswas->total(),
            'count' => $siswas->count(),
            'current_page' => $siswas->currentPage()
        ]);
        
        return view('koordinator.siswa', compact('siswas'));
    } catch (\Exception $e) {
        Log::error('Error in indexSiswa', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
    }
}

    public function createSiswa()
    {
         return view('koordinator.siswa.create');
    }

    public function storeSiswa(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nis' => 'required|string|unique:students',
            'password' => 'required|min:8|confirmed', // PASTIKAN ADA 'confirmed'
            'alamat' => 'required|string',
            'kelas' => 'required|string',
            'tgl_lahir' => 'required|date',
            'no_hp' => 'required|string',
        ]);

        // Buat user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'email_verified_at' => now(),
        ]);

        // Buat student
        Student::create([
            'user_id' => $user->id,
            'nama_lengkap' => $validated['name'],
            'nis' => $validated['nis'],
            'tgl_lahir' => $validated['tgl_lahir'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'kelas' => $validated['kelas'],
        ]);

        return redirect()->route('koordinator.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
            
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
    }
}

    public function showSiswa($id)
{
    try {
        $siswa = Student::with('user')->findOrFail($id);
        
        Log::info('Siswa detail accessed', [
            'siswa_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        return view('koordinator.siswa.show', compact('siswa'));
    } catch (\Exception $e) {
        Log::error('Error showing siswa', [
            'error' => $e->getMessage(),
            'siswa_id' => $id
        ]);
        
        return back()->with('error', 'Siswa tidak ditemukan');
    }
}

    public function editSiswa($id)
{
    try {
        $siswa = Student::with('user')->findOrFail($id);
        
        Log::info('Edit siswa form accessed', [
            'siswa_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        return view('koordinator.siswa.edit', compact('siswa'));
    } catch (\Exception $e) {
        Log::error('Error showing edit siswa form', [
            'error' => $e->getMessage(),
            'siswa_id' => $id
        ]);
        
        return back()->with('error', 'Siswa tidak ditemukan');
    }
}

    public function updateSiswa(Request $request, $id)
{
    try {
        $siswa = Student::findOrFail($id);
        $user = $siswa->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'required|string|unique:students,nis,' . $siswa->id,
            'alamat' => 'required|string',
            'kelas' => 'required|string',
            'tgl_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update user
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Update student - SESUAIKAN DENGAN MODEL
        $siswa->update([
            'nama_lengkap' => $validated['name'],
            'nis' => $validated['nis'],
            'tgl_lahir' => $validated['tgl_lahir'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'kelas' => $validated['kelas'],
        ]);

        Log::info('Student updated', [
            'student_id' => $siswa->id,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('koordinator.siswa.index')
            ->with('success', 'Siswa berhasil diupdate');
                
    } catch (\Exception $e) {
        Log::error('Error updating student', [
            'error' => $e->getMessage()
        ]);
        
        return back()->withInput()
            ->with('error', 'Gagal mengupdate siswa: ' . $e->getMessage());
    }
}

    public function destroySiswa($id)
    {
        try {
            $siswa = Student::findOrFail($id);
            $user = $siswa->user;

            $siswa->delete();
            $user->delete();

            Log::info('Student deleted', [
                'student_id' => $id,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->route('koordinator.siswa.index')
                ->with('success', 'Siswa berhasil dihapus');
                
        } catch (\Exception $e) {
            Log::error('Error deleting student', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal menghapus siswa');
        }
    }

    /**
     * UPGRADE SISWA KE GURU BK
     */
    
    /**
     * Show form untuk upgrade siswa ke guru BK
     */
    public function showUpgradeForm($userId)
    {
        try {
            $user = User::with('student')->findOrFail($userId);
            
            // Validasi: hanya siswa yang bisa di-upgrade
            if ($user->role !== 'siswa') {
                return redirect()->route('koordinator.siswa.index')
                    ->with('error', 'Hanya user dengan role siswa yang dapat di-upgrade');
            }

            return view('koordinator.upgrade-guru', compact('user'));

        } catch (\Exception $e) {
            Log::error('Error showing upgrade form', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
            
            return redirect()->route('koordinator.siswa.index')
                ->with('error', 'User tidak ditemukan');
        }
    }

    /**
     * Process upgrade siswa ke guru BK
     */
    public function upgradeToGuru(Request $request, $userId)
    {
        try {
            $user = User::with('student')->findOrFail($userId);
            
            // Validasi: hanya siswa yang bisa di-upgrade
            if ($user->role !== 'siswa') {
                return back()->with('error', 'Hanya user dengan role siswa yang dapat di-upgrade');
            }

            $request->validate([
                'nip' => 'required|string|unique:counselors,nip',
                'specialization' => 'required|string|max:255',
                'office_hours' => 'required|string|max:255',
            ]);

            DB::transaction(function () use ($user, $request) {
                // Update role user
                $user->update([
                    'role' => 'guru_bk',
                    'updated_at' => now()
                ]);

                // Hapus data siswa jika ada
                if ($user->student) {
                    $user->student->delete();
                }

                // Buat data guru BK - SESUAIKAN DENGAN MODEL
                Counselor::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $user->name,  // GUNAKAN nama_lengkap
                    'nip' => $request->nip,
                    'no_hp' => $user->phone,        // GUNAKAN no_hp
                    'specialization' => $request->specialization,
                    'office_hours' => $request->office_hours,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            Log::info('User upgraded to guru BK', [
                'user_id' => $user->id,
                'upgraded_by' => auth()->id()
            ]);

            return redirect()->route('koordinator.guru.index')
                ->with('success', 'User ' . $user->name . ' berhasil di-upgrade menjadi Guru BK');

        } catch (\Exception $e) {
            Log::error('Error upgrading user to guru BK', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
            
            return back()->withInput()
                ->with('error', 'Gagal meng-upgrade user: ' . $e->getMessage());
        }
    }

    /**
     * LAPORAN - METHOD YANG DIBUTUHKAN
     */
    public function laporan()
    {
        try {
            // Data statistik untuk laporan
            $stats = [
                'total_siswa' => User::where('role', 'siswa')->count(),
                'total_guru_bk' => User::where('role', 'guru_bk')->count(),
                'total_konseling' => JanjiKonseling::count(),
                'konseling_bulan_ini' => JanjiKonseling::whereMonth('created_at', now()->month)->count(),
                'konseling_selesai' => JanjiKonseling::where('status', 'selesai')->count(),
                'konseling_pending' => JanjiKonseling::where('status', 'menunggu')->count(),
            ];

            // Data konseling per bulan untuk chart
            $konselingPerBulan = JanjiKonseling::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

            // Data konseling berdasarkan jenis bimbingan
            $konselingByJenis = JanjiKonseling::select(
                'jenis_bimbingan',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('jenis_bimbingan')
            ->get();

            // Data konseling berdasarkan status
            $konselingByStatus = JanjiKonseling::select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get();

            Log::info('Laporan accessed', [
                'user_id' => auth()->id()
            ]);

            return view('koordinator.laporan', compact(
                'stats',
                'konselingPerBulan',
                'konselingByJenis',
                'konselingByStatus'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in laporan', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * PENGATURAN - METHOD YANG DIBUTUHKAN
     */
    public function pengaturan()
    {
        try {
            $user = Auth::user();
            return view('koordinator.pengaturan', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in pengaturan', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal memuat halaman pengaturan');
        }
    }
}