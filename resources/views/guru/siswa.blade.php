@extends('layouts.app')

@section('title', 'Daftar Siswa Bimbingan - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Siswa Bimbingan</h1>
        <p class="text-gray-600">Kelola data siswa bimbingan Anda</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-download"></i>
            <span>Export Data</span>
        </button>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-filter"></i>
            <span>Filter</span>
        </button>
    </div>

    <!-- Search Box -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           placeholder="Cari siswa berdasarkan nama atau email..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
                    <i class="fas fa-search"></i>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Siswa Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Data Siswa</h2>
            <p class="text-sm text-gray-600">Total {{ $siswa->total() }} siswa terdaftar</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Bergabung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $item->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->class ?? 'Belum ditentukan' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 transition duration-150 flex items-center space-x-1">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat</span>
                                </button>
                                <button class="text-green-600 hover:text-green-900 transition duration-150 flex items-center space-x-1">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat</span>
                                </button>
                                <button class="text-purple-600 hover:text-purple-900 transition duration-150 flex items-center space-x-1">
                                    <i class="fas fa-comment"></i>
                                    <span>Konseling</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-users-slash text-3xl mb-2 block"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($siswa->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $siswa->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Siswa</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $siswa->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Aktif Bulan Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ DB::table('counseling_sessions')
                            ->join('users', 'counseling_sessions.student_id', '=', 'users.id')
                            ->whereMonth('counseling_sessions.session_date', now()->month)
                            ->distinct('student_id')
                            ->count('student_id') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Rata-rata Konseling</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format(DB::table('counseling_sessions')->count() / max($siswa->total(), 1), 1) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection