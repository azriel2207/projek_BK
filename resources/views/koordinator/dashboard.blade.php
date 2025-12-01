@extends('layouts.koordinator-layout')

@section('title', 'Dashboard Koordinator BK - Sistem BK')

@section('page-content')
<div class="container mx-auto p-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-blue-100">Kelola dan monitor seluruh aktivitas bimbingan konseling sekolah</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Siswa -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['total_siswa'] ?? 0 }}
                            </p>
                            <p class="text-blue-600 text-sm mt-2">
                                <i class="fas fa-users"></i> Terdaftar
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Guru BK -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Guru BK</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['total_guru'] ?? 0 }}
                            </p>
                            <p class="text-green-600 text-sm mt-2">
                                <i class="fas fa-user-tie"></i> Aktif
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Konseling Bulan Ini -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Konseling Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['konseling_bulan_ini'] ?? 0 }}
                            </p>
                            <p class="text-purple-600 text-sm mt-2">
                                <i class="fas fa-comments"></i> Sesi
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Kasus Prioritas -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Menunggu Konfirmasi</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['menunggu_konfirmasi'] ?? 0 }}
                            </p>
                            <p class="text-orange-600 text-sm mt-2">
                                <i class="fas fa-exclamation-triangle"></i> Perlu tindakan
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Statistik Konseling -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Konseling Per Jenis</h3>
                    <div class="space-y-4">
                        @if(isset($jenisKonselingData) && count($jenisKonselingData) > 0)
                            @foreach($jenisKonselingData as $jenis)
                                @php
                                    $label = data_get($jenis, 'color.label', (data_get($jenis, 'jenis') ? ucfirst(data_get($jenis, 'jenis')) : 'Lainnya'));
                                    $bg = data_get($jenis, 'color.bg', 'gray');
                                    $total = data_get($jenis, 'total', 0);
                                    $percentage = number_format(data_get($jenis, 'percentage', 0), 1);
                                    $colorMap = [
                                        'blue' => '#2563eb',
                                        'green' => '#16a34a',
                                        'purple' => '#7c3aed',
                                        'orange' => '#f97316',
                                        'gray' => '#6b7280'
                                    ];
                                    $barColor = $colorMap[$bg] ?? $colorMap['gray'];
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium">{{ $label }}</span>
                                        <span class="text-sm font-bold">{{ $total }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-8 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6"/><path d="M7 10l5-5 5 5"/><path d="M12 15V3"/></svg>
                                <p class="font-medium">Belum ada data untuk ditampilkan.</p>
                                <p class="text-sm mt-1">Data jenis konseling akan muncul setelah ada sesi yang tercatat.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('koordinator.guru.index') }}" class="block bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Tambah Guru BK</p>
                        </a>
                        <a href="{{ route('koordinator.laporan') }}" class="block bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-file-alt text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Buat Laporan</p>
                        </a>
                        
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="{{ route('koordinator.laporan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        @php
                            $statusColor = match($activity->status ?? 'pending') {
                                'selesai' => 'bg-green-100',
                                'menunggu' => 'bg-yellow-100',
                                default => 'bg-blue-100'
                            };
                            $statusIcon = match($activity->status ?? 'pending') {
                                'selesai' => 'fa-check text-green-600',
                                'menunggu' => 'fa-clock text-yellow-600',
                                default => 'fa-calendar text-blue-600'
                            };
                        @endphp
                        <div class="{{ $statusColor }} p-2 rounded-full">
                            <i class="fas {{ $statusIcon }}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $activity->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst($activity->jenis_bimbingan ?? 'general') }} - {{ ucfirst($activity->status ?? 'pending') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
@endsection