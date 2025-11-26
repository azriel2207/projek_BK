@extends('layouts.app')

@section('title', 'Detail Catatan Konseling - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Catatan Konseling</h1>
            <p class="text-gray-600">Lihat detail lengkap catatan konseling siswa</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.catatan') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Catatan</span>
            </a>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Student Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                        Informasi Siswa
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Siswa</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $catatan->nama_siswa }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $catatan->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Konseling</label>
                            <p class="text-gray-900">
                                {{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Waktu</label>
                            <p class="text-gray-900">{{ $catatan->waktu }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jenis Bimbingan</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($catatan->jenis_bimbingan == 'Akademik') bg-blue-100 text-blue-800
                                @elseif($catatan->jenis_bimbingan == 'Karir') bg-green-100 text-green-800
                                @elseif($catatan->jenis_bimbingan == 'Personal') bg-purple-100 text-purple-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $catatan->jenis_bimbingan }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($catatan->status == 'selesai') bg-green-100 text-green-800
                                @elseif($catatan->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                                @elseif($catatan->status == 'menunggu') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($catatan->status == 'selesai') Selesai
                                @elseif($catatan->status == 'dikonfirmasi') Dikonfirmasi
                                @elseif($catatan->status == 'menunggu') Menunggu
                                @else Dibatalkan
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Session Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-green-500"></i>
                        Keluhan / Permasalahan Awal
                    </h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($catatan->keluhan)
                            <p class="text-gray-700 whitespace-pre-line">{{ $catatan->keluhan }}</p>
                        @else
                            <p class="text-gray-500 italic">Tidak ada keluhan yang dicatat</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-purple-500"></i>
                        Catatan Konselor
                    </h2>
                    
                    <div class="bg-blue-50 rounded-lg p-4">
                        @if($catatan->catatan_konselor)
                            <p class="text-gray-700 whitespace-pre-line">{{ $catatan->catatan_konselor }}</p>
                        @else
                            <p class="text-gray-500 italic">Belum ada catatan dari konselor</p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('guru.catatan') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                        
                        @if(!$catatan->catatan_konselor)
                        <a href="{{ route('guru.catatan.tambah', $catatan->id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Catatan</span>
                        </a>
                        @else
                        <a href="{{ route('guru.catatan.tambah', $catatan->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                            <i class="fas fa-edit"></i>
                            <span>Edit Catatan</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-history text-orange-500"></i>
                Timeline Konseling
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                    <div>
                        <p class="font-medium text-gray-900">Konseling Dibuat</p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
                
                @if($catatan->updated_at != $catatan->created_at)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                    <div>
                        <p class="font-medium text-gray-900">Terakhir Diperbarui</p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($catatan->updated_at)->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
                @endif
                
                @if($catatan->status == 'selesai' && $catatan->catatan_konselor)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-3 h-3 bg-purple-500 rounded-full mt-2"></div>
                    <div>
                        <p class="font-medium text-gray-900">Konseling Diselesaikan</p>
                        <p class="text-sm text-gray-500">
                            Dengan catatan dari konselor
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection