@extends('layouts.koordinator-layout')

@section('title', 'Data Siswa')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Siswa</h1>
            <p class="text-gray-600 mt-1">Kelola data siswa di sistem BK</p>
        </div>
        <a href="{{ route('koordinator.siswa.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Siswa
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $siswas->total() }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        @php
            $totalAktif = DB::table('users')->where('role', 'siswa')->count();
        @endphp
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Siswa Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAktif }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <i class="fas fa-user-check text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        @php
            $totalKonseling = DB::table('janji_konselings')->count();
        @endphp
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Konseling</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalKonseling }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <i class="fas fa-comments text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        @php
            $rataRata = $totalAktif > 0 ? round($totalKonseling / $totalAktif, 1) : 0;
        @endphp
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Konseling/Siswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $rataRata }}</p>
                </div>
                <div class="bg-orange-100 p-4 rounded-lg">
                    <i class="fas fa-chart-line text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form action="{{ route('koordinator.siswa.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Cari nama atau NIS..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                Cari
            </button>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswas as $index => $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $siswas->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $siswa->nis ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $siswa->nama_lengkap ?? $siswa->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $siswa->kelas ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $siswa->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $siswa->phone ?? $siswa->no_hp ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($siswa->created_at)->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('koordinator.siswa.show', $siswa->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Lihat
                                </a>
                                <a href="{{ route('koordinator.siswa.edit', $siswa->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900">
                                    Edit
                                </a>
                                <form action="{{ route('koordinator.siswa.destroy', $siswa->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="font-medium">Tidak ada data siswa</p>
                                <p class="text-sm">Klik tombol "Tambah Siswa" untuk menambahkan data baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($siswas->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-2 text-blue-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">Total: {{ $siswas->total() }} siswa</span>
        </div>
    </div>
</div>
@endsection