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
     * List semua siswa
     */
    public function daftarSiswa(Request $request)
    {
        $query = Student::with('user', 'waliKelas');

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter berdasarkan wali kelas
        if ($request->filled('wali_kelas')) {
            $query->where('wali_kelas_id', $request->wali_kelas);
        }

        $siswa = $query->paginate(15);
        $daftarKelas = Student::distinct()->pluck('kelas')->sort();
        $daftarWaliKelas = User::where('role', 'wali_kelas')->get();

        return view('koordinator.daftar-siswa', compact('siswa', 'daftarKelas', 'daftarWaliKelas'));
    }

    /**
     * Detail siswa
     */
    public function detailSiswa($studentId)
    {
        $siswa = Student::with('user', 'waliKelas', 'behaviors', 'identity')->findOrFail($studentId);
        
        return view('koordinator.detail-siswa', compact('siswa'));
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
     * MANAJEMEN AKUN SISWA
     * Koordinator dapat menambah akun siswa dan mengatur data diri siswa
     */
    public function indexSiswa(Request $request)
    {
        try {
            $query = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('users.role', 'siswa')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'students.id as student_id',
                    'students.nis',
                    'students.kelas',
                    'students.tgl_lahir',
                    'students.nama_lengkap',
                    'users.created_at'
                );

            // Filter pencarian
            if ($search = $request->input('search')) {
                $search = trim($search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('users.name', 'like', '%' . $search . '%')
                          ->orWhere('users.email', 'like', '%' . $search . '%')
                          ->orWhere('students.nis', 'like', '%' . $search . '%')
                          ->orWhere('students.nama_lengkap', 'like', '%' . $search . '%');
                    });
                }
            }

            $siswa = $query->orderBy('students.nis', 'asc')
                ->paginate(15)
                ->withQueryString();

            return view('koordinator.siswa.index', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error loading siswa list', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data siswa');
        }
    }

    /**
     * Form untuk tambah akun siswa baru
     */
    public function createSiswa()
    {
        return view('koordinator.siswa.create');
    }

    /**
     * Simpan akun siswa baru
     */
    public function storeSiswa(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nis' => 'required|string|unique:students,nis',
                'password' => 'required|min:8|confirmed',
                'nama_lengkap' => 'nullable|string|max:255',
                'tgl_lahir' => 'nullable|date',
                'kelas' => 'nullable|string|max:50',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string',
            ]);

            // Buat user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
                'nis_verified' => true, // Langsung verified karena dibuat oleh koordinator
            ]);

            // Buat student record
            Student::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'] ?? $validated['name'],
                'nis' => $validated['nis'],
                'tgl_lahir' => $validated['tgl_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
            ]);

            // Langsung verifikasi email
            $user->markEmailAsVerified();

            Log::info('New siswa account created by koordinator', [
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'created_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.siswa.index')
                ->with('success', 'Akun siswa berhasil dibuat. Akun dapat langsung digunakan untuk login.');
        } catch (\Exception $e) {
            Log::error('Error creating siswa account', [
                'error' => $e->getMessage(),
                'created_by' => Auth::id()
            ]);
            return back()->withInput()->with('error', 'Gagal membuat akun siswa: ' . $e->getMessage());
        }
    }

    /**
     * Form edit siswa
     */
    public function editSiswa($id)
    {
        try {
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
                    'students.tgl_lahir',
                    'students.alamat',
                    'students.no_hp'
                )
                ->first();

            if (!$siswa) {
                abort(404, 'Siswa tidak ditemukan');
            }

            return view('koordinator.siswa.edit', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error loading edit siswa form', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);
            return back()->with('error', 'Gagal memuat data siswa');
        }
    }

    /**
     * Update data siswa (koordinator bisa update data diri)
     */
    public function updateSiswa(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'siswa')->firstOrFail();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'nis' => 'required|string',
                'nama_lengkap' => 'nullable|string|max:255',
                'tgl_lahir' => 'nullable|date',
                'kelas' => 'nullable|string|max:50',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string',
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

            // Update student record
            $student = Student::where('user_id', $user->id)->first();
            
            if ($student) {
                $student->update([
                    'nama_lengkap' => $validated['nama_lengkap'] ?? $validated['name'],
                    'nis' => $validated['nis'],
                    'tgl_lahir' => $validated['tgl_lahir'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                    'kelas' => $validated['kelas'] ?? null,
                ]);
            } else {
                Student::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $validated['nama_lengkap'] ?? $validated['name'],
                    'nis' => $validated['nis'],
                    'tgl_lahir' => $validated['tgl_lahir'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                    'kelas' => $validated['kelas'] ?? null,
                ]);
            }

            Log::info('Siswa account updated by koordinator', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.siswa.index')
                ->with('success', 'Data siswa berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating siswa account', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'updated_by' => Auth::id()
            ]);
            return back()->withInput()->with('error', 'Gagal mengupdate siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus akun siswa
     */
    public function deleteSiswa($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'siswa')->firstOrFail();
            
            $user->delete();

            Log::info('Siswa account deleted by koordinator', [
                'user_id' => $user->id,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.siswa.index')
                ->with('success', 'Akun siswa berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting siswa account', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'deleted_by' => Auth::id()
            ]);
            return back()->with('error', 'Gagal menghapus akun siswa: ' . $e->getMessage());
        }
    }

    /**
     * MANAJEMEN AKUN WALI KELAS
     * Koordinator dapat menambah, edit, dan hapus akun wali kelas
     */
    public function indexWaliKelas(Request $request)
    {
        try {
            $query = DB::table('users')
                ->where('users.role', 'wali_kelas')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.created_at',
                    DB::raw('COUNT(students.id) as jumlah_siswa')
                )
                ->leftJoin('students', 'users.id', '=', 'students.wali_kelas_id')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.created_at');

            // Filter pencarian
            if ($search = $request->input('search')) {
                $search = trim($search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('users.name', 'like', '%' . $search . '%')
                          ->orWhere('users.email', 'like', '%' . $search . '%');
                    });
                }
            }

            $waliKelas = $query->orderBy('users.created_at', 'desc')
                ->paginate(15)
                ->withQueryString();

            return view('koordinator.wali-kelas.index', compact('waliKelas'));
        } catch (\Exception $e) {
            Log::error('Error loading wali kelas list', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data wali kelas');
        }
    }

    /**
     * Form untuk tambah akun wali kelas baru
     */
    public function createWaliKelas()
    {
        return view('koordinator.wali-kelas.create');
    }

    /**
     * Simpan akun wali kelas baru
     */
    public function storeWaliKelas(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ]);

            // Buat user dengan role wali_kelas
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'wali_kelas',
                'phone' => $validated['phone'] ?? null,
                'email_verified_at' => now(),
            ]);

            Log::info('New wali kelas account created by koordinator', [
                'user_id' => $user->id,
                'name' => $validated['name'],
                'created_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.wali-kelas.index')
                ->with('success', 'Akun wali kelas berhasil dibuat. Akun dapat langsung digunakan untuk login.');
        } catch (\Exception $e) {
            Log::error('Error creating wali kelas account', [
                'error' => $e->getMessage(),
                'created_by' => Auth::id()
            ]);
            return back()->withInput()->with('error', 'Gagal membuat akun wali kelas: ' . $e->getMessage());
        }
    }

    /**
     * Form edit akun wali kelas
     */
    public function editWaliKelas($id)
    {
        try {
            $waliKelas = DB::table('users')
                ->where('users.id', $id)
                ->where('users.role', 'wali_kelas')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    DB::raw('COUNT(students.id) as jumlah_siswa')
                )
                ->leftJoin('students', 'users.id', '=', 'students.wali_kelas_id')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
                ->first();

            if (!$waliKelas) {
                abort(404, 'Wali kelas tidak ditemukan');
            }

            return view('koordinator.wali-kelas.edit', compact('waliKelas'));
        } catch (\Exception $e) {
            Log::error('Error showing edit wali kelas form', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return back()->with('error', 'Wali kelas tidak ditemukan');
        }
    }

    /**
     * Update akun wali kelas
     */
    public function updateWaliKelas(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'wali_kelas') {
                abort(403, 'User bukan wali kelas');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|min:8|confirmed',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            Log::info('Wali kelas account updated by koordinator', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.wali-kelas.index')
                ->with('success', 'Data wali kelas berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error updating wali kelas account', [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Gagal mengupdate wali kelas: ' . $e->getMessage());
        }
    }

    /**
     * Hapus akun wali kelas
     */
    public function destroyWaliKelas($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'wali_kelas') {
                abort(403, 'User bukan wali kelas');
            }

            // Cek apakah ada siswa yang ditugaskan
            $siswaCount = Student::where('wali_kelas_id', $user->id)->count();
            if ($siswaCount > 0) {
                return back()->with('error', 'Tidak dapat menghapus wali kelas yang masih memiliki siswa di kelasnya. Pindahkan siswa terlebih dahulu.');
            }

            $user->delete();

            Log::info('Wali kelas account deleted by koordinator', [
                'user_id' => $id,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('koordinator.wali-kelas.index')
                ->with('success', 'Akun wali kelas berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting wali kelas account', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal menghapus akun wali kelas: ' . $e->getMessage());
        }
    }

};
