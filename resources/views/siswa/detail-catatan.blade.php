@extends('layouts.siswa-layout')

@section('page-content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('siswa.catatan.index') }}"
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
            <p class="text-gray-600 mt-2">Catatan dan rekomendasi dari guru BK Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom Kiri - Informasi Guru & Konseling -->
            <div class="lg:col-span-1">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-tie text-blue-500"></i>
                        Dari Guru BK
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                        <p><strong class="text-gray-700">Nama Guru:</strong><br/> <span class="text-gray-900">{{ $catatan->guru_bk ?? '-' }}</span></p>
                        <p><strong class="text-gray-700">Tanggal Catatan:</strong><br/> <span class="text-gray-900">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</span></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-green-500"></i>
                        Status
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                        <p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Diterima
                            </span>
                        </p>
                        <p><strong class="text-gray-700">Dibuat:</strong><br/>
                            <span class="text-gray-900 text-sm">
                                <span class="relative-time" data-timestamp="{{ \Carbon\Carbon::parse($catatan->created_at)->toIso8601String() }}">{{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}</span>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Isi Catatan & Keluhan -->
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informasi Konseling
                    </h3>
                    <div class="space-y-3 bg-blue-50 rounded-lg p-4">
                        @if($janji)
                            <p><strong class="text-gray-700">Jenis Bimbingan:</strong><br/> 
                                <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                    @if($janji->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                                    @elseif($janji->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                                    @elseif($janji->jenis_bimbingan == 'pribadi') bg-blue-100 text-blue-800
                                    @elseif($janji->jenis_bimbingan == 'sosial') bg-orange-100 text-orange-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($janji->jenis_bimbingan ?? 'Umum') }}
                                </span>
                            </p>
                            <p><strong class="text-gray-700">Waktu:</strong><br/> <span class="text-gray-900">{{ $janji->waktu ?? '-' }}</span></p>
                        @else
                            <p class="text-gray-500">Informasi konseling tidak tersedia</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-notebook text-blue-500"></i>
                        Isi Catatan
                    </h3>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200 shadow-sm mb-8">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed text-base font-normal">
                                @php
                                    // Pisahkan catatan dan rekomendasi
                                    $parts = explode('--- REKOMENDASI ---', $catatan->isi ?? '');
                                    echo trim($parts[0]);
                                @endphp
                            </p>
                        </div>
                        <div class="mt-6 pt-6 border-t border-blue-200 text-xs text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span class="relative-time-wrapper">
                                Catatan dibuat pada <span class="relative-time" data-timestamp="{{ \Carbon\Carbon::parse($catatan->created_at)->toIso8601String() }}">{{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}</span> oleh {{ $catatan->guru_bk ?? 'Guru BK' }}
                            </span>
                        </div>
                    </div>

                    <!-- Rekomendasi Section -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        Rekomendasi
                    </h3>
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border-2 border-yellow-200 shadow-sm">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed text-base font-normal">
                                @php
                                    if(count($parts) > 1) {
                                        echo trim($parts[1]);
                                    } else {
                                        echo 'Tidak ada rekomendasi khusus';
                                    }
                                @endphp
                            </p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
