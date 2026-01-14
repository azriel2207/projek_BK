@extends('layouts.siswa-layout')

@section('title', 'Detail Riwayat Konseling')

@section('page-content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.riwayat-konseling') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-6">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Detail Riwayat Konseling</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Informasi Konseling -->
            <div class="lg:col-span-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Informasi Konseling
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Jenis Bimbingan</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($detail->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                            @elseif($detail->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                            @elseif($detail->jenis_bimbingan == 'pribadi') bg-purple-100 text-purple-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($detail->jenis_bimbingan ?? 'Umum') }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($detail->status == 'selesai') bg-green-100 text-green-800
                            @elseif($detail->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                            @elseif($detail->status == 'menunggu') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($detail->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Tanggal Konseling</label>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Waktu</label>
                        <p class="text-gray-900 font-medium">{{ $detail->waktu ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Lokasi</label>
                        <p class="text-gray-900 font-medium">{{ $detail->lokasi ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Guru BK</label>
                        <p class="text-gray-900 font-medium">{{ $detail->guru_bk ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Keluhan & Catatan -->
            <div class="lg:col-span-2">
                <!-- Keluhan -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-green-500"></i>
                        Keluhan / Permasalahan
                    </h3>
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                            {{ $detail->keluhan ?? 'Tidak ada deskripsi keluhan' }}
                        </p>
                    </div>
                </div>

                <!-- Catatan Konselor -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-notebook text-purple-500"></i>
                        Catatan Konselor
                    </h3>

                    @if($detail->catatan_konselor && !empty(trim($detail->catatan_konselor)))
                    <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                            {{ $detail->catatan_konselor }}
                        </p>
                    </div>
                    @else
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <p class="text-gray-600 italic">
                            <i class="fas fa-info-circle mr-2"></i>
                            Catatan akan ditampilkan setelah guru BK memberikan catatan konseling.
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Keterangan Tambahan (jika ada) -->
                @if($detail->keterangan ?? null)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-yellow-500"></i>
                        Keterangan Tambahan
                    </h3>
                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                            {{ $detail->keterangan }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-xl shadow-sm p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <i class="fas fa-history text-orange-500"></i>
            Timeline Konseling
        </h3>

        <div class="space-y-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                <div>
                    <p class="font-medium text-gray-900">Janji Konseling Dibuat</p>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($detail->created_at)->format('d M Y H:i') }}</p>
                </div>
            </div>

            @if($detail->status == 'dikonfirmasi')
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                <div>
                    <p class="font-medium text-gray-900">Janji Dikonfirmasi</p>
                    <p class="text-sm text-gray-500">Guru BK telah mengkonfirmasi janji konseling</p>
                </div>
            </div>
            @elseif($detail->status == 'selesai')
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                <div>
                    <p class="font-medium text-gray-900">Konseling Selesai</p>
                    <p class="text-sm text-gray-500">Sesi konseling telah selesai dan catatan tersimpan</p>
                </div>
            </div>
            @elseif($detail->status == 'dibatalkan')
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-3 h-3 bg-red-500 rounded-full mt-2"></div>
                <div>
                    <p class="font-medium text-gray-900">Janji Dibatalkan</p>
                    <p class="text-sm text-gray-500">Janji konseling telah dibatalkan</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
