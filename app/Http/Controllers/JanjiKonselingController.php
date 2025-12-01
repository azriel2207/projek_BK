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
            
            // Janji menunggu konfirmasi - sorted DESC (newest first)
            $janjiMenunggu = JanjiKonseling::where('user_id', $user->id)
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'desc')
                ->get();

            // Janji yang sudah dikonfirmasi - sorted DESC (newest first)
            $janjiKonfirmasi = JanjiKonseling::where('user_id', $user->id)
                ->where('status', 'dikonfirmasi')
                ->where('tanggal', '>=', now()->format('Y-m-d'))
                ->orderBy('tanggal', 'desc')
                ->get();

            // Ambil list guru BK dari tabel users dengan role guru_bk/guru
            $gurus = DB::table('users')
                ->whereIn('role', ['guru_bk', 'guru'])
                ->select('id', 'name')
                ->get();

            return view('siswa.janji-konseling', compact('janjiMenunggu', 'janjiKonfirmasi', 'gurus'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required',
            'keluhan' => 'required|string|min:10',
            'jenis_bimbingan' => 'required|in:pribadi,belajar,karir,sosial',
            'guru_id' => 'nullable|exists:users,id'
        ]);

        // Get guru name if guru_id provided, otherwise use input guru_bk if exists
        $guruId = $request->guru_id;
        $guruName = 'Guru BK';
        
        if ($guruId) {
            $guru = DB::table('users')->find($guruId);
            $guruName = $guru->name ?? 'Guru BK';
        }

        JanjiKonseling::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keluhan' => $request->keluhan,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'guru_id' => $guruId,
            'guru_bk' => $guruName,
            'status' => 'menunggu'
        ]);

        return redirect()->route('siswa.janji-konseling')
            ->with('success', 'Janji konseling berhasil dibuat. Menunggu konfirmasi dari guru BK.');
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
        $janji = JanjiKonseling::where('user_id', Auth::id())->findOrFail($id);
        $janji->update(['status' => 'dibatalkan']);

        return redirect()->route('siswa.janji-konseling')
            ->with('success', 'Janji konseling berhasil dibatalkan');
    }
}