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
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-plus"></i>
            <span>Tambah Catatan</span>
        </button>
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
                                <p class="text-sm text-gray-600">{{ $item->topic }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->session_date)->format('d M Y') }}</span>
                                <div class="flex space-x-1 mt-1">
                                    <button class="text-blue-600 hover:text-blue-900 transition duration-150">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 transition duration-150">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 transition duration-150">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <p class="text-gray-700">{{ Str::limit($item->notes, 200) }}</p>
                        </div>
                        
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</span>
                                </span>
                                @if($item->follow_up_required)
                                <span class="flex items-center space-x-1 text-orange-600">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Perlu Tindak Lanjut</span>
                                </span>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $item->session_type ?? 'Konseling Reguler' }}
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
                            {{ DB::table('counseling_notes')->where('counselor_id', auth()->id())->whereMonth('created_at', now()->month)->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Perlu Tindak Lanjut</span>
                        <span class="font-semibold text-orange-600">
                            {{ DB::table('counseling_notes')->where('counselor_id', auth()->id())->where('follow_up_required', true)->count() }}
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Sesi Terbaru</h3>
                <div class="space-y-3">
                    @php
                        $recentSessions = DB::table('counseling_sessions')
                            ->join('users', 'counseling_sessions.student_id', '=', 'users.id')
                            ->where('counselor_id', auth()->id())
                            ->orderBy('session_date', 'desc')
                            ->limit(3)
                            ->get(['users.name', 'counseling_sessions.session_date', 'counseling_sessions.topic']);
                    @endphp
                    
                    @foreach($recentSessions as $session)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="font-medium text-sm">{{ $session->name }}</p>
                        <p class="text-xs text-gray-600">{{ $session->topic }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->session_date)->format('d M H:i') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection