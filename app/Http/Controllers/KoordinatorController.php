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
                // gunakan created_at agar konsisten dengan query lain
                'konseling_bulan_ini' => DB::table('janji_konselings')
                    ->whereMonth('created_at', now()->month)
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

            $jenisKonselingData = $jenisKonselingRaw->map(function ($item) use ($totalAll) {
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
                'user_id' => Auth::id(),
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
    public function indexGuru(Request $request)
    {
        try {
            // Query dari users table dengan role guru_bk, left join dengan counselors
            $query = DB::table('users')
                ->leftJoin('counselors', 'users.id', '=', 'counselors.user_id')
                ->where('users.role', 'guru_bk')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'counselors.id as counselor_id',
                    'counselors.nama_lengkap',
                    'counselors.nip',
                    'counselors.no_hp',
                    'counselors.specialization',
                    'counselors.office_hours',
                    'users.created_at'
                );

            // Filter pencarian berdasarkan nama atau email
            if ($search = $request->input('search')) {
                $search = trim($search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('users.name', 'like', '%' . $search . '%')
                          ->orWhere('users.email', 'like', '%' . $search . '%')
                          ->orWhere('counselors.nama_lengkap', 'like', '%' . $search . '%')
                          ->orWhere('counselors.nip', 'like', '%' . $search . '%');
                    });
                }
            }

            $gurus = $query->orderBy('users.created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            return view('koordinator.guru', compact('gurus'));
        } catch (\Exception $e) {
            Log::error('Error loading guru BK list', ['error' => $e->getMessage()]);
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

            // Buat user (hanya dengan kolom yang ada di users table)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru_bk',
                'email_verified_at' => now(),
            ]);

            // Buat counselor record secara manual dengan semua data
            Counselor::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['name'],
                'nip' => $validated['nip'],
                'no_hp' => $validated['phone'],
                'specialization' => $validated['specialization'] ?? 'Umum',
                'office_hours' => $validated['office_hours'] ?? '08:00 - 16:00',
            ]);

            Log::info('New guru BK created', [
                'user_id' => $user->id,
                'created_by' => Auth::id()
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
            // Query user dengan role guru_bk, left join dengan counselors
            $guru = DB::table('users')
                ->leftJoin('counselors', 'users.id', '=', 'counselors.user_id')
                ->where('users.id', $id)
                ->where('users.role', 'guru_bk')
                ->select(
                    'users.*',
                    'counselors.id as counselor_id',
                    'counselors.nama_lengkap',
                    'counselors.nip',
                    'counselors.no_hp',
                    'counselors.specialization',
                    'counselors.office_hours'
                )
                ->first();

            if (!$guru) {
                abort(404, 'Guru BK tidak ditemukan');
            }

            return view('koordinator.guru.edit', compact('guru'));
        } catch (\Exception $e) {
            Log::error('Error editing guru BK', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }

    public function updateGuru(Request $request, $id)
    {
        try {
            // Get user
            $user = User::findOrFail($id);

            if ($user->role !== 'guru_bk') {
                abort(403, 'User bukan guru BK');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|string|unique:users,phone,' . $user->id,
                'nip' => 'nullable|string',
                'specialization' => 'nullable|string',
                'office_hours' => 'nullable|string',
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Update user (hanya kolom yang ada di users table)
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update counselor record
            $counselor = Counselor::where('user_id', $user->id)->first();
            
            if ($counselor) {
                $counselor->update([
                    'nama_lengkap' => $validated['name'],
                    'nip' => $validated['nip'] ?? $counselor->nip,
                    'no_hp' => $validated['phone'],
                    'specialization' => $validated['specialization'] ?? $counselor->specialization,
                    'office_hours' => $validated['office_hours'] ?? $counselor->office_hours,
                ]);
            }

            Log::info('Guru BK updated', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
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
            $user = User::findOrFail($id);

            if ($user->role !== 'guru_bk') {
                abort(403, 'User bukan guru BK');
            }

            // Hapus counselor record jika ada
            Counselor::where('user_id', $user->id)->delete();
            
            // Hapus user
            $user->delete();

            Log::info('Guru BK deleted', [
                'user_id' => $id,
                'deleted_by' => Auth::id()
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
            // Query user dengan role guru_bk, left join dengan counselors
            $guru = DB::table('users')
                ->leftJoin('counselors', 'users.id', '=', 'counselors.user_id')
                ->where('users.id', $id)
                ->where('users.role', 'guru_bk')
                ->select(
                    'users.*',
                    'counselors.id as counselor_id',
                    'counselors.nama_lengkap',
                    'counselors.nip',
                    'counselors.no_hp',
                    'counselors.specialization',
                    'counselors.office_hours'
                )
                ->first();

            if (!$guru) {
                abort(404, 'Guru BK tidak ditemukan');
            }

            return view('koordinator.guru.show', compact('guru'));
        } catch (\Exception $e) {
            Log::error('Error showing guru BK', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->with('error', 'Guru BK tidak ditemukan');
        }
    }

    /**
     * MANAJEMEN SISWA
     */
    public function indexSiswa()
    {
        try {
            // Query dari users table dengan role siswa, left join dengan students
            $siswas = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('users.role', 'siswa')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'students.id as student_id',
                    'students.nis',
                    'students.nama_lengkap',
                    'students.kelas',
                    'students.no_hp',
                    'users.created_at'
                )
                ->orderBy('users.created_at', 'desc')
                ->paginate(10);

            Log::info('Data siswa loaded', [
                'total' => $siswas->total(),
                'count' => $siswas->count(),
                'current_page' => $siswas->currentPage()
            ]);

            // sesuaikan nama view agar konsisten dengan route 'koordinator.siswa.index'
            return view('koordinator.siswa.index', compact('siswas'));
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
                'password' => 'required|min:8|confirmed',
                'alamat' => 'required|string',
                'kelas' => 'required|string',
                'tgl_lahir' => 'required|date',
                'no_hp' => 'required|string',
            ]);

            // Buat user (hanya dengan kolom yang ada di users table)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'phone' => $validated['no_hp'],
            ]);

            // Buat student record secara manual karena data tidak ada di users table
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
            Log::error('Error storing siswa', ['error' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    public function showSiswa($id)
    {
        try {
            // Query user dengan role siswa, left join dengan students
            $siswa = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('users.id', $id)
                ->where('users.role', 'siswa')
                ->select(
                    'users.*',
                    'students.id as student_id',
                    'students.nis',
                    'students.nama_lengkap',
                    'students.kelas',
                    'students.alamat',
                    'students.tgl_lahir',
                    'students.no_hp'
                )
                ->first();

            if (!$siswa) {
                abort(404, 'Siswa tidak ditemukan');
            }

            Log::info('Siswa detail accessed', [
                'user_id' => $id,
                'accessed_by' => Auth::id()
            ]);

            return view('koordinator.siswa.show', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error showing siswa', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return back()->with('error', 'Siswa tidak ditemukan');
        }
    }

    public function editSiswa($id)
    {
        try {
            // Query user dengan role siswa, left join dengan students
            $siswa = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('users.id', $id)
                ->where('users.role', 'siswa')
                ->select(
                    'users.*',
                    'students.id as student_id',
                    'students.nis',
                    'students.nama_lengkap',
                    'students.kelas',
                    'students.alamat',
                    'students.tgl_lahir',
                    'students.no_hp'
                )
                ->first();

            if (!$siswa) {
                abort(404, 'Siswa tidak ditemukan');
            }

            Log::info('Edit siswa form accessed', [
                'user_id' => $id,
                'accessed_by' => Auth::id()
            ]);

            return view('koordinator.siswa.edit', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error showing edit siswa form', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return back()->with('error', 'Siswa tidak ditemukan');
        }
    }

    public function updateSiswa(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'siswa') {
                abort(403, 'User bukan siswa');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'nis' => 'nullable|string',
                'alamat' => 'nullable|string',
                'kelas' => 'nullable|string',
                'tgl_lahir' => 'nullable|date',
                'no_hp' => 'nullable|string',
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Update user (hanya kolom yang ada di users table)
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['no_hp'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update student record
            $siswa = Student::where('user_id', $user->id)->first();
            
            if ($siswa) {
                $siswa->update([
                    'nama_lengkap' => $validated['name'],
                    'nis' => $validated['nis'] ?? $siswa->nis,
                    'tgl_lahir' => $validated['tgl_lahir'] ?? $siswa->tgl_lahir,
                    'alamat' => $validated['alamat'] ?? $siswa->alamat,
                    'no_hp' => $validated['no_hp'] ?? $siswa->no_hp,
                    'kelas' => $validated['kelas'] ?? $siswa->kelas,
                ]);
            }

            Log::info('Student updated', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
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
            $user = User::findOrFail($id);

            if ($user->role !== 'siswa') {
                abort(403, 'User bukan siswa');
            }

            // Delete associated student record
            $siswa = Student::where('user_id', $user->id)->first();
            if ($siswa) {
                $siswa->delete();
            }

            // Delete user
            $user->delete();

            Log::info('Student deleted', [
                'user_id' => $id,
                'deleted_by' => Auth::id()
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
                // Update user dengan nip agar ProfileSync menggunakannya
                $user->update([
                    'role' => 'guru_bk',
                    'nip' => $request->nip,
                    'updated_at' => now()
                ]);

                // Hapus data siswa jika ada
                if ($user->student) {
                    $user->student->delete();
                }

                // Update counselor record (Observer sudah membuat saat role berubah)
                $counselor = Counselor::where('user_id', $user->id)->first();
                if ($counselor) {
                    $counselor->update([
                        'nip' => $request->nip,
                        'specialization' => $request->specialization,
                        'office_hours' => $request->office_hours,
                    ]);
                }
            });

            Log::info('User upgraded to guru BK', [
                'user_id' => $user->id,
                'upgraded_by' => Auth::id()
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
                'user_id' => Auth::id()
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

   
};