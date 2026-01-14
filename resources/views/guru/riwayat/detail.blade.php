@extends('layouts.guru-layout')

@section('title', 'Detail Catatan Konseling')

@section('page-content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('guru.riwayat.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Card Detail --}}
    <div class="bg-white rounded-xl shadow-sm p-8">
        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Detail Catatan Konseling</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap dari catatan konseling ini</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom Kiri - Informasi Siswa & Konseling -->
            <div class="lg:col-span-1">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-500"></i>
                        Informasi Siswa
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                        <p><strong class="text-gray-700">Nama:</strong><br/> <span class="text-gray-900">{{ $catatan->nama_siswa ?? '-' }}</span></p>
                        <p><strong class="text-gray-700">ID Siswa:</strong><br/> <span class="text-gray-900">{{ $catatan->user_id }}</span></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-green-500"></i>
                        Informasi Konseling
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                        <p><strong class="text-gray-700">Tanggal:</strong><br/> <span class="text-gray-900">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</span></p>
                        <p><strong class="text-gray-700">Jenis Bimbingan:</strong><br/> 
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                @if($catatan->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                                @elseif($catatan->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                                @elseif($catatan->jenis_bimbingan == 'pribadi') bg-purple-100 text-purple-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($catatan->jenis_bimbingan ?? 'Umum') }}
                            </span>
                        </p>
                        <p><strong class="text-gray-700">Status:</strong><br/>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ ucfirst($catatan->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Keluhan & Catatan -->
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-orange-500"></i>
                        Keluhan / Masalah
                    </h3>
                    <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $catatan->keluhan ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-notebook text-purple-500"></i>
                        Isi Catatan
                    </h3>
                    <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $catatan->isi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
