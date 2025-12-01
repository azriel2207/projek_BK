@extends('layouts.guru-layout')

@section('title', 'Detail Siswa - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Siswa</h1>
            <p class="text-gray-600">Informasi lengkap dan riwayat konseling siswa</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.siswa') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Siswa</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                        Profil Siswa
                    </h2>
                    
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $siswa->name }}</h3>
                        <p class="text-gray-600">{{ $siswa->email }}</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID Siswa</label>
                            <p class="text-gray-900 font-mono">{{ $siswa->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kelas</label>
                            <p class="text-gray-900">{{ $siswa->class ?? 'Belum ditentukan' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                            <p class="text-gray-900">
                                {{ \Carbon\Carbon::parse($siswa->created_at)->format('d-m-Y') }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('guru.siswa.konseling.create', $siswa->id) }}" 
                           class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center gap-2 transition">
                            <i class="fas fa-comments"></i>
                            <span>Buat Jadwal Konseling</span>
                        </a>
                        
                        <a href="{{ route('guru.siswa.kelas.edit', $siswa->id) }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center gap-2 transition">
                            <i class="fas fa-edit"></i>
                            <span>Edit Kelas</span>
                        </a>
                        
                        <a href="{{ route('guru.siswa.riwayat', $siswa->id) }}" 
                           class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center gap-2 transition">
                            <i class="fas fa-history"></i>
                            <span>Lihat Riwayat Lengkap</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Konseling History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-purple-500"></i>
                            Riwayat Konseling Terbaru
                        </h2>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            Total: {{ $riwayatKonseling->count() }}
                        </span>
                    </div>

                    @if($riwayatKonseling->count() > 0)
                        <div class="space-y-4">
                            @foreach($riwayatKonseling as $konseling)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($konseling->jenis_bimbingan == 'Akademik') bg-blue-100 text-blue-800
                                            @elseif($konseling->jenis_bimbingan == 'Karir') bg-green-100 text-green-800
                                            @elseif($konseling->jenis_bimbingan == 'Personal') bg-purple-100 text-purple-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $konseling->jenis_bimbingan }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($konseling->status == 'selesai') bg-green-100 text-green-800
                                        @elseif($konseling->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                                        @elseif($konseling->status == 'menunggu') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($konseling->status == 'selesai') Selesai
                                        @elseif($konseling->status == 'dikonfirmasi') Dikonfirmasi
                                        @elseif($konseling->status == 'menunggu') Menunggu
                                        @else Dibatalkan
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Tanggal:</span>
                                        <span class="text-gray-900">
                                            {{ \Carbon\Carbon::parse($konseling->tanggal)->format('d-m-Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Waktu:</span>
                                        <span class="text-gray-900">{{ $konseling->waktu }}</span>
                                    </div>
                                </div>
                                
                                @if($konseling->keluhan)
                                <div class="mt-2">
                                    <span class="font-medium text-gray-700">Keluhan:</span>
                                    <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $konseling->keluhan }}</p>
                                </div>
                                @endif
                                
                                @if($konseling->catatan_konselor)
                                <div class="mt-2">
                                    <span class="font-medium text-gray-700">Catatan:</span>
                                    <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $konseling->catatan_konselor }}</p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-lg">Belum ada riwayat konseling</p>
                            <p class="text-gray-400 text-sm mt-1">Siswa ini belum pernah melakukan konseling</p>
                            <a href="{{ route('guru.siswa.konseling.create', $siswa->id) }}" 
                               class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                Buat Jadwal Konseling Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Konseling</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $riwayatKonseling->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $riwayatKonseling->where('status', 'selesai')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Menunggu</h3>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $riwayatKonseling->where('status', 'menunggu')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection