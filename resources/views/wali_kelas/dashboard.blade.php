@extends('layouts.wali-kelas-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Dashboard Wali Kelas</h1>
        <p class="text-gray-600 mt-2">Kelola siswa dan pantau perkembangan mereka</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_siswa'] }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Siswa Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['siswa_aktif'] }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Catatan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_catatan'] }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-file-alt text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('wali_kelas.daftar-siswa') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow p-6 transition transform hover:scale-105">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-list text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm opacity-80">Lihat Semua</p>
                    <p class="text-lg font-semibold">Daftar Siswa</p>
                </div>
            </div>
        </a>
        <a href="{{ route('wali_kelas.create-siswa') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow p-6 transition transform hover:scale-105">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm opacity-80">Buat Baru</p>
                    <p class="text-lg font-semibold">Siswa Baru</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Students Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-gray-600"></i>
                Siswa Terbaru
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($siswa->take(5) as $s)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $s->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $s->kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $s->user->email }}</td>
                            <td class="px-6 py-4">
                                @if($s->nis_verified)
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
                                <a href="{{ route('wali_kelas.detail-siswa', $s->id) }}" class="text-blue-600 hover:text-blue-900 font-medium transition">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Belum ada siswa yang ditambahkan</p>
                                    <p class="text-gray-400 text-sm">Klik "Tambah Siswa" untuk menambahkan siswa ke kelas Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Wali Kelas</h3>
                <p class="text-blue-800">
                    Anda dapat mengelola siswa dalam kelas Anda, memberikan catatan perkembangan, 
                    dan mengupdate data diri siswa. Gunakan menu di sidebar untuk mengakses fitur-fitur tersebut.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
