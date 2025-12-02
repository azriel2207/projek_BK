@extends('layouts.koordinator-layout')

@section('title', 'Kelola Guru BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Guru BK</h1>
            <p class="text-gray-600 mt-2">Manage counselor/guru BK information</p>
        </div>
        <a href="{{ route('koordinator.guru.create') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg transition">
            + Tambah Guru BK
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Guru BK</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $gurus->total() }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Konseling Semua Guru</p>
                    @php
                        $totalKonseling = DB::table('janji_konselings')->count();
                    @endphp
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalKonseling }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <i class="fas fa-comments text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Rata-rata Konseling/Guru</p>
                    @php
                        $rataRata = $gurus->total() > 0 ? round($totalKonseling / $gurus->total(), 1) : 0;
                    @endphp
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $rataRata }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form action="{{ route('koordinator.guru.index') }}" method="GET" class="flex gap-3 items-center flex-wrap">
            <input type="text" name="search" placeholder="Cari guru berdasarkan nama, email, atau NIP..." 
                   class="flex-1 min-w-[250px] px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition inline-flex items-center gap-2">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('koordinator.guru.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition inline-flex items-center gap-2">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Guru BK</h3>
            @if(request('search'))
                <p class="text-sm text-gray-600">Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></p>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NO</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NAMA</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NIP</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">EMAIL</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">TELEPON</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">SPESIALISASI</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">KONSELING</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">SISWA DIBIMBING</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">TANGGAL BERGABUNG</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                    @php
                        $jumlahKonseling = DB::table('janji_konselings')
                            ->where('janji_konselings.guru_bk', $guru->name)
                            ->count();
                        $siswaDibimbing = DB::table('janji_konselings')
                            ->where('janji_konselings.guru_bk', $guru->name)
                            ->distinct('janji_konselings.user_id')
                            ->count('janji_konselings.user_id');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ ($gurus->currentPage() - 1) * $gurus->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->nama_lengkap ?? $guru->name }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->nip ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->email }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->no_hp ?? $guru->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                {{ $guru->specialization ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <div class="flex items-center">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $jumlahKonseling }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <div class="flex items-center">
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $siswaDibimbing }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ \Carbon\Carbon::parse($guru->created_at)->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <div class="flex items-center space-x-2">
                                <!-- Tombol View -->
                                <a href="{{ route('koordinator.guru.show', $guru->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="View Detail">
                                    View
                                </a>
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('koordinator.guru.edit', $guru->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="Edit">
                                    Edit
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('koordinator.guru.destroy', $guru->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="Hapus"
                                            onclick="confirmDelete('{{ addslashes($guru->nama_lengkap ?? $guru->name) }}'); return false;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-user-tie text-5xl mb-3 text-gray-300"></i>
                                <p class="text-gray-500 font-medium mb-1">Guru BK tidak ditemukan</p>
                                @if(request('search'))
                                    <p class="text-sm text-gray-400">Tidak ada hasil untuk pencarian "<strong>{{ request('search') }}</strong>"</p>
                                    <a href="{{ route('koordinator.guru.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-3">Lihat semua guru BK</a>
                                @else
                                    <p class="text-sm text-gray-400">Belum ada data guru BK terdaftar</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $gurus->links() }}
    </div>
</div>
@endsection