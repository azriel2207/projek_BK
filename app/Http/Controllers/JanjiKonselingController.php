<?php

namespace App\Http\Controllers;

use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JanjiKonselingController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Janji mendatang
            $janjiMendatang = JanjiKonseling::where('user_id', $user->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->where('tanggal', '>=', now()->format('Y-m-d'))
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu', 'asc')
                ->get();

            // Riwayat
            $riwayatJanji = JanjiKonseling::where('user_id', $user->id)
                ->where(function($query) {
                    $query->whereIn('status', ['selesai', 'dibatalkan'])
                          ->orWhere('tanggal', '<', now()->format('Y-m-d'));
                })
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu', 'desc')
                ->get();

            return view('siswa.janji-konseling', compact('janjiMendatang', 'riwayatJanji'));
            
        } catch (\Exception $e) {
            // Fallback jika error
            $janjiMendatang = [];
            $riwayatJanji = [];
            return view('siswa.janji-konseling', compact('janjiMendatang', 'riwayatJanji'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required',
            'keluhan' => 'required|string|min:10',
            'jenis_bimbingan' => 'required|in:pribadi,belajar,karir,sosial',
            'guru_bk' => 'required|string'
        ]);

        JanjiKonseling::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keluhan' => $request->keluhan,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'guru_bk' => $request->guru_bk,
            'status' => 'menunggu'
        ]);

        return redirect()->route('siswa.janji-konseling')
            ->with('success', 'Janji konseling berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $janji = JanjiKonseling::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required',
            'keluhan' => 'required|string|min:10',
            'jenis_bimbingan' => 'required|in:pribadi,belajar,karir,sosial',
            'guru_bk' => 'required|string'
        ]);

        $janji->update([
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keluhan' => $request->keluhan,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'guru_bk' => $request->guru_bk
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

    public function yourMethod()
    {
        return "JanjiKonselingController berhasil diakses!";
    }
}