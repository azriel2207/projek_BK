@extends('layouts.siswa-layout')

@section('title', 'Detail Riwayat Konseling')

@section('page-content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('siswa.riwayat-konseling') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    {{-- Card Detail --}}
    <div class="bg-white rounded-xl shadow-sm p-8">
        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Detail Riwayat Konseling</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap riwayat konseling Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom Kiri - Informasi Konseling -->
            <div class="lg:col-span-1">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informasi Konseling
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                        <p><strong class="text-gray-700">Jenis Bimbingan:</strong><br/> 
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                @if($detail->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                                @elseif($detail->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                                @elseif($detail->jenis_bimbingan == 'pribadi') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($detail->jenis_bimbingan ?? 'Umum') }}
                            </span>
                        </p>
                        <p><strong class="text-gray-700">Status:</strong><br/>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                @if($detail->status == 'selesai') bg-green-100 text-green-800
                                @elseif($detail->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                                @elseif($detail->status == 'menunggu') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($detail->status) }}
                            </span>
                        </p>
                        <p><strong class="text-gray-700">Tanggal Konseling:</strong><br/> <span class="text-gray-900">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d M Y') }}</span></p>
                        <p><strong class="text-gray-700">Waktu:</strong><br/> <span class="text-gray-900">{{ $detail->waktu ?? '-' }}</span></p>
                        <p><strong class="text-gray-700">Guru BK:</strong><br/> <span class="text-gray-900">{{ $detail->guru_bk ?? '-' }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Keluhan & Catatan -->
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-orange-500"></i>
                        Keluhan / Permasalahan
                    </h3>
                    <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed !text-left">{{ $detail->keluhan ?? 'Tidak ada deskripsi keluhan' }}</p>
                    </div>
                </div>

                <!-- Isi Catatan -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-notebook text-blue-500"></i>
                        Isi Catatan
                    </h3>
                    @if($detail->catatan_konselor && !empty(trim($detail->catatan_konselor)))
                    <div class="bg-blue-50 rounded-lg p-6 border-2 border-blue-200 shadow-sm">
                        <p class="text-gray-800 whitespace-pre-wrap leading-relaxed text-base font-normal !text-left">
                            @php
                                $text = $detail->catatan_konselor;
                                if (strpos($text, '--- REKOMENDASI ---') !== false) {
                                    $parts = explode('--- REKOMENDASI ---', $text);
                                    echo trim($parts[0]);
                                } else {
                                    echo $text;
                                }
                            @endphp
                        </p>
                        <div class="mt-4 pt-4 border-t border-blue-200 text-xs text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>Catatan dari Guru BK</span>
                        </div>
                    </div>
                    @else
                    <div class="bg-blue-50 rounded-lg p-6 border-2 border-blue-200">
                        <p class="text-blue-700 italic flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Catatan akan ditampilkan setelah guru BK memberikan catatan konseling.
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Rekomendasi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        Rekomendasi
                    </h3>
                    @if($detail->catatan_konselor && !empty(trim($detail->catatan_konselor)))
                        @php
                            $text = $detail->catatan_konselor;
                            $hasRekomendasi = strpos($text, '--- REKOMENDASI ---') !== false;
                            if ($hasRekomendasi) {
                                $parts = explode('--- REKOMENDASI ---', $text);
                                $rekomendasi = trim($parts[1]);
                            } else {
                                $rekomendasi = '';
                            }
                        @endphp
                        
                        @if(!empty($rekomendasi))
                        <div class="bg-yellow-50 rounded-lg p-6 border-2 border-yellow-200 shadow-sm">
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed text-base font-normal !text-left">{{ $rekomendasi }}</p>
                            <div class="mt-4 pt-4 border-t border-yellow-200 text-xs text-gray-600 flex items-center gap-2">
                                <i class="fas fa-lightbulb text-yellow-500"></i>
                                <span>Rekomendasi dari Guru BK</span>
                            </div>
                        </div>
                        @else
                        <div class="bg-yellow-50 rounded-lg p-6 border-2 border-yellow-200">
                            <p class="text-yellow-700 italic flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Belum ada rekomendasi dari Guru BK.
                            </p>
                        </div>
                        @endif
                    @else
                    <div class="bg-yellow-50 rounded-lg p-6 border-2 border-yellow-200">
                        <p class="text-yellow-700 italic flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Rekomendasi akan ditampilkan setelah guru BK memberikan catatan konseling.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
