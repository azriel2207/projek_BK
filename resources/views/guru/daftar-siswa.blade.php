@extends('layouts.guru-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Siswa</h1>
        <p class="text-gray-600 mt-2">Lihat dan cek data semua siswa di sekolah</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('guru.siswa-list') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Nama, NIS, atau Kelas"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Wali Kelas</label>
                <select name="wali_kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Wali Kelas</option>
                    @foreach($daftarWaliKelas as $wk)
                        <option value="{{ $wk->id }}" {{ request('wali_kelas') === $wk->id ? 'selected' : '' }}>{{ $wk->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('guru.siswa-list') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <p class="text-gray-600 text-sm font-medium">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $siswa->total() }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <p class="text-gray-600 text-sm font-medium">Siswa Aktif</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $siswa->getCollection()->filter(function($s) { return $s && $s->nis_verified; })->count() }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
            <p class="text-gray-600 text-sm font-medium">Dengan Wali Kelas</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $siswa->getCollection()->filter(function($s) { return $s->wali_kelas_id; })->count() }}
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Wali Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($siswa as $s)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $s->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $s->nis }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $s->kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ $s->user->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $s->waliKelas?->name ?? 'Belum ditentukan' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($s && $s->nis_verified)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('guru.siswa-list.detail', $s->id) }}" class="text-blue-600 hover:text-blue-900 font-medium transition">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada siswa yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($siswa->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $siswa->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
