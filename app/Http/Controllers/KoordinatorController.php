<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Counselor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:koordinator');
    }

    // Dashboard Koordinator
    public function dashboard()
    {
        $totalGuru = Counselor::count();
        $totalSiswa = Student::count();
        $totalUser = User::count();

        return view('koordinator.dashboard', compact('totalGuru', 'totalSiswa', 'totalUser'));
    }

    // List semua guru BK
    public function indexGuru()
    {
        $gurus = Counselor::with('user')->paginate(10);
        return view('koordinator.guru.index', compact('gurus'));
    }

    // Form tambah guru BK
    public function createGuru()
    {
        return view('koordinator.guru.create');
    }

    // Simpan guru BK baru
    public function storeGuru(Request $request)
    {
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
            'role' => 'guru',
        ]);

        // Buat counselor
        Counselor::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'specialization' => $validated['specialization'],
            'office_hours' => $validated['office_hours'],
        ]);

        return redirect()->route('koordinator.guru.index')
            ->with('success', 'Guru BK berhasil ditambahkan');
    }

    // Form edit guru BK
    public function editGuru($id)
    {
        $guru = Counselor::findOrFail($id);
        return view('koordinator.guru.edit', compact('guru'));
    }

    // Update guru BK
    public function updateGuru(Request $request, $id)
    {
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

        return redirect()->route('koordinator.guru.index')
            ->with('success', 'Guru BK berhasil diupdate');
    }

    // Hapus guru BK
    public function destroyGuru($id)
    {
        $guru = Counselor::findOrFail($id);
        $user = $guru->user;

        $guru->delete();
        $user->delete();

        return redirect()->route('koordinator.guru.index')
            ->with('success', 'Guru BK berhasil dihapus');
    }

    // Lihat detail guru BK
    public function showGuru($id)
    {
        $guru = Counselor::with('user', 'counselingSessions')->findOrFail($id);
        return view('koordinator.guru.show', compact('guru'));
    }
}