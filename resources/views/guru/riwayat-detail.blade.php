@extends('layouts.guru-layout')

@section('title', 'Detail Catatan - Sistem BK')

@section('page-content')
<div class="w-full min-h-screen bg-gray-50 px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Catatan Konseling</h1>
            <p class="text-gray-600">Informasi lengkap catatan konseling siswa</p>
        </div>

        <!-- Back Button & Actions -->
        <div class="mb-6 flex gap-3">
            <a href="{{ route('guru.riwayat.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('guru.siswa.riwayat', $catatan->user_id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-history"></i>
                <span>Lihat Riwayat Siswa</span>
            </a>
            <a href="{{ route('guru.siswa.detail', $catatan->user_id) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-user"></i>
                <span>Detail Siswa</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Student Information Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Siswa</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Nama Siswa</label>
                            <p class="text-gray-900 font-semibold">{{ $catatan->nama_siswa }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Email</label>
                            <p class="text-gray-900 text-sm break-all">{{ $catatan->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Tanggal Konseling</label>
                            <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Jenis Bimbingan</label>
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-medium mt-1">
                                {{ $catatan->jenis_bimbingan ?? 'Umum' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Status</label>
                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-medium mt-1">
                                {{ ucfirst($catatan->status ?? 'selesai') }}
                            </span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span class="block mt-1">Dibuat: {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Catatan Content - Full Width -->
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Isi Catatan</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 min-h-96">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-wrap font-base text-left">{{ $catatan->isi_catatan ?? $catatan->catatan_konselor }}</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Dibuat pada: {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Janji Konseling Info -->
                @if($janji)
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Informasi Janji Konseling</h2>
                    
                    <div class="grid grid-cols-2 gap-6 bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div>
                            <label class="text-sm font-medium text-gray-600 block mb-2">Waktu Konseling</label>
                            <p class="text-gray-900 font-medium">{{ $janji->waktu ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-600 block mb-2">Lokasi</label>
                            <p class="text-gray-900 font-medium">{{ $janji->lokasi ?? '-' }}</p>
                        </div>
                        
                        @if($janji->keluhan)
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600 block mb-2">Keluhan/Masalah</label>
                            <p class="text-gray-800 leading-relaxed">{{ $janji->keluhan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection