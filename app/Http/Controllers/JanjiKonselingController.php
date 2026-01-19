<?php

namespace App\Http\Controllers;

use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JanjiKonselingController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            \Log::info('=== JANJI KONSELING INDEX ===');
            \Log::info('User ID: ' . $user->id);
            
            // Janji menunggu konfirmasi - sorted DESC (newest first)
            $janjiMenunggu = JanjiKonseling::where('user_id', $user->id)
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Janji menunggu count: ' . $janjiMenunggu->count());
            foreach ($janjiMenunggu as $j) {
                \Log::info('  - ID: ' . $j->id . ', Status: ' . $j->status . ', Tanggal: ' . $j->tanggal);
            }

            // Janji yang sudah dikonfirmasi - sorted DESC (newest first)
            $janjiKonfirmasi = JanjiKonseling::where('user_id', $user->id)
                ->where('status', 'dikonfirmasi')
                ->where('tanggal', '>=', now()->format('Y-m-d'))
                ->orderBy('tanggal', 'desc')
                ->get();

            \Log::info('Janji konfirmasi count: ' . $janjiKonfirmasi->count());

            // Ambil list guru BK dari tabel users dengan role guru_bk/guru
            $gurus = DB::table('users')
                ->whereIn('role', ['guru_bk', 'guru'])
                ->select('id', 'name')
                ->get();

            \Log::info('=== END INDEX ===');

            return view('siswa.janji-konseling', compact('janjiMenunggu', 'janjiKonfirmasi', 'gurus'));
            
        } catch (\Exception $e) {
            \Log::error('Error in janji index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        \Log::info('=== JANJI KONSELING STORE REQUEST ===');
        \Log::info('Request input:', $request->all());
        \Log::info('Auth user ID: ' . Auth::id());
        \Log::info('Auth user:', [
            'id' => Auth::id(),
            'email' => Auth::user()?->email,
            'role' => Auth::user()?->role
        ]);
        
        $validated = $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required',
            'keluhan' => 'required|string|min:10',
            'jenis_bimbingan' => 'required|in:pribadi,belajar,karir,sosial',
            'guru_id' => 'nullable|exists:users,id'
        ]);

        \Log::info('Validation passed. Data:', $validated);

        $userId = Auth::id();
        
        if (!$userId) {
            \Log::error('Auth user ID is null!');
            return redirect()->back()
                ->with('error', 'User tidak terautentikasi dengan baik. Silakan login kembali.')
                ->withInput();
        }
        
        // Check untuk prevent duplicate submission dalam 3 detik
        // Ini hanya untuk mencegah rapid double-click pada submit button
        $recentJanji = JanjiKonseling::where('user_id', $userId)
            ->where('tanggal', $request->tanggal)
            ->where('waktu', $request->waktu)
            ->where('jenis_bimbingan', $request->jenis_bimbingan)
            ->where('status', 'menunggu')
            ->where('created_at', '>=', now()->subSeconds(3))
            ->first();
        
        if ($recentJanji) {
            \Log::warning('Duplicate janji detected for user ' . $userId);
            return redirect()->route('siswa.janji-konseling')
                ->with('warning', 'Janji dengan data yang sama baru saja dibuat. Mohon tunggu beberapa saat...');
        }

        // Get guru name if guru_id provided
        $guruId = $request->guru_id;
        $guruName = 'Guru BK';
        
        if ($guruId) {
            $guru = DB::table('users')->find($guruId);
            $guruName = $guru->name ?? 'Guru BK';
        }

        try {
            $janji = JanjiKonseling::create([
                'user_id' => $userId,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'keluhan' => $request->keluhan,
                'jenis_bimbingan' => $request->jenis_bimbingan,
                'guru_id' => $guruId,
                'guru_bk' => $guruName,
                'status' => 'menunggu',
                'is_archived' => false
            ]);

            \Log::info('Janji created successfully', [
                'id' => $janji->id,
                'user_id' => $janji->user_id,
                'status' => $janji->status,
                'created_at' => $janji->created_at
            ]);

            // Verify data was saved correctly
            $verify = JanjiKonseling::find($janji->id);
            \Log::info('Verification - Janji exists in database:', [
                'found' => $verify ? true : false,
                'user_id' => $verify?->user_id,
                'status' => $verify?->status
            ]);

            \Log::info('=== END ===');

            return redirect()->route('siswa.janji-konseling')
                ->with('success', 'Janji konseling berhasil dibuat. Menunggu konfirmasi dari guru BK.');
        } catch (\Exception $e) {
            \Log::error('Failed to create janji: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            \Log::info('=== END (ERROR) ===');
            
            return redirect()->back()
                ->with('error', 'Gagal membuat janji konseling: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $janji = JanjiKonseling::where('user_id', $user->id)->findOrFail($id);
        
        $gurus = DB::table('users')
            ->whereIn('role', ['guru_bk', 'guru'])
            ->select('id', 'name')
            ->get();

        return view('siswa.janji-konseling-edit', compact('janji', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $janji = JanjiKonseling::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required',
            'keluhan' => 'required|string|min:10',
            'jenis_bimbingan' => 'required|in:pribadi,belajar,karir,sosial',
            'guru_id' => 'nullable|exists:users,id'
        ]);

        // Get guru name if guru_id provided
        $guruId = $request->guru_id;
        $guruName = $janji->guru_bk;
        
        if ($guruId) {
            $guru = DB::table('users')->find($guruId);
            $guruName = $guru->name ?? 'Guru BK';
        }

        $janji->update([
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keluhan' => $request->keluhan,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'guru_id' => $guruId,
            'guru_bk' => $guruName
        ]);

        return redirect()->route('siswa.janji-konseling')
            ->with('success', 'Janji konseling berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $janji = JanjiKonseling::where('user_id', $user->id)->findOrFail($id);

        // Prevent cancellation of completed or archived sessions
        if ($janji->status === 'selesai') {
            return redirect()->route('siswa.janji-konseling')
                ->with('error', 'Tidak dapat membatalkan janji yang sudah selesai. Janji telah diarsipkan dan tidak dapat diubah.');
        }

        if ($janji->status === 'dibatalkan') {
            return redirect()->route('siswa.janji-konseling')
                ->with('warning', 'Janji ini sudah dibatalkan sebelumnya');
        }

        // Only allow cancellation of 'menunggu' and 'dikonfirmasi' status
        if (!in_array($janji->status, ['menunggu', 'dikonfirmasi'])) {
            return redirect()->route('siswa.janji-konseling')
                ->with('error', 'Status janji tidak memungkinkan pembatalan');
        }

        $janji->update(['status' => 'dibatalkan']);

        return redirect()->route('siswa.janji-konseling')
            ->with('success', 'Janji konseling berhasil dibatalkan');
    }

    /**
     * Archive completed counseling session
     * Called after session is marked as 'selesai'
     */
    public function archive($id)
    {
        $user = Auth::user();
        $janji = JanjiKonseling::where('user_id', $user->id)->findOrFail($id);

        if ($janji->status !== 'selesai') {
            return redirect()->back()
                ->with('error', 'Hanya janji yang sudah selesai yang dapat diarsipkan');
        }

        $janji->update([
            'is_archived' => true,
            'archived_at' => now()
        ]);

        return redirect()->route('siswa.riwayat-konseling')
            ->with('success', 'Janji konseling berhasil diarsipkan');
    }
}