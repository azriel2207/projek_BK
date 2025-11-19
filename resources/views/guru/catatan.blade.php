@extends('layouts.app')

@section('title', 'Catatan Konseling - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Catatan Konseling</h1>
        <p class="text-gray-600">Kelola catatan dan progress konseling siswa</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-download"></i>
            <span>Export Catatan</span>
        </button>
    </div>

    <!-- Catatan List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Catatan Konseling</h2>
                    <p class="text-sm text-gray-600">Total {{ $catatan->total() }} catatan</p>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($catatan as $item)
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->nama_siswa }}</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst($item->jenis_bimbingan ?? 'Konseling') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                <div class="flex space-x-1 mt-1">
                                    <a href="{{ route('guru.catatan.detail', $item->id) }}" class="text-green-600 hover:text-green-900 transition duration-150">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-900 transition duration-150">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <p class="text-gray-700">{{ Str::limit($item->keluhan ?? 'Tidak ada keluhan', 200) }}</p>
                        </div>
                        
                        @if($item->catatan_konselor)
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-800 mb-1">Catatan Konselor:</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($item->catatan_konselor, 150) }}</p>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</span>
                                </span>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <p class="text-lg">Belum ada catatan konseling</p>
                        <p class="text-sm mt-2">Mulai dengan membuat catatan untuk sesi konseling yang telah dilakukan</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($catatan->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $catatan->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Catatan</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Catatan</span>
                        <span class="font-semibold">{{ $catatan->total() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Bulan Ini</span>
                        <span class="font-semibold">
                            {{ DB::table('janji_konselings')
                                ->where('status', 'selesai')
                                ->whereMonth('tanggal', now()->month)
                                ->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Konseling</span>
                        <span class="font-semibold">
                            {{ DB::table('janji_konselings')->where('status', 'selesai')->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center justify-center space-x-2 transition duration-200">
                        <i class="fas fa-sticky-note"></i>
                        <span>Template Catatan</span>
                    </button>
                    <button class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg flex items-center justify-center space-x-2 transition duration-200">
                        <i class="fas fa-search"></i>
                        <span>Cari Catatan</span>
                    </button>
                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg flex items-center justify-center space-x-2 transition duration-200">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analisis</span>
                    </button>
                </div>
            </div>

            <!-- Recent Sessions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Konseling Terbaru</h3>
                <div class="space-y-3">
                    @php
                        $recentSessions = DB::table('janji_konselings')
                            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
                            ->select('users.name', 'janji_konselings.tanggal', 'janji_konselings.jenis_bimbingan')
                            ->where('janji_konselings.status', 'selesai')
                            ->orderBy('janji_konselings.tanggal', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp
                    
                    @forelse($recentSessions as $session)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="font-medium text-sm">{{ $session->name }}</p>
                        <p class="text-xs text-gray-600">{{ ucfirst($session->jenis_bimbingan ?? 'Konseling') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->tanggal)->format('d M Y') }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada sesi terbaru</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection