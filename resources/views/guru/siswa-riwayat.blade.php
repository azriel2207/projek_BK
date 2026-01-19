@extends('layouts.guru-layout')

@section('title', 'Riwayat Konseling Siswa - Sistem BK')

@section('page-content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('guru.siswa.detail', $siswa->id) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Detail Siswa</span>
        </a>
    </div>

    {{-- Card Detail --}}
    <div class="bg-white rounded-xl shadow-sm p-8">
        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Konseling Lengkap</h1>
            <p class="text-gray-600 mt-2">Semua riwayat konseling {{ $siswa->name }}</p>
        </div>

        {{-- Informasi Siswa --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i>
                    Informasi Siswa
                </h3>
                <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                    <p><strong class="text-gray-700">Nama:</strong><br/> <span class="text-gray-900">{{ $siswa->name }}</span></p>
                    <p><strong class="text-gray-700">Email:</strong><br/> <span class="text-gray-900">{{ $siswa->email }}</span></p>
                    <p><strong class="text-gray-700">Kelas:</strong><br/> <span class="text-gray-900">{{ $siswa->class ?? 'Belum ada kelas' }}</span></p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-green-500"></i>
                    Statistik
                </h3>
                <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                    <p><strong class="text-gray-700">Total Konseling:</strong><br/> <span class="text-2xl font-bold text-gray-900">{{ $riwayatKonseling->total() }}</span></p>
                </div>
            </div>
        </div>

        {{-- Daftar Konseling --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-list text-blue-500"></i>
                Daftar Konseling
            </h3>

            @if($riwayatKonseling->count() > 0)
                <div class="space-y-4">
                    @foreach($riwayatKonseling as $konseling)
                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-md transition p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                            <!-- Tanggal & Waktu -->
                            <div class="flex items-start gap-3">
                                <i class="fas fa-calendar-alt text-blue-600 text-xl mt-1"></i>
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal & Waktu</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($konseling->tanggal)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ $konseling->waktu }}</p>
                                </div>
                            </div>

                            <!-- Jenis Bimbingan -->
                            <div class="flex items-start gap-3">
                                <i class="fas fa-tag text-green-600 text-xl mt-1"></i>
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Jenis Bimbingan</p>
                                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                        @if($konseling->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                                        @elseif($konseling->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                                        @elseif($konseling->jenis_bimbingan == 'pribadi') bg-blue-100 text-blue-800
                                        @elseif($konseling->jenis_bimbingan == 'sosial') bg-orange-100 text-orange-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($konseling->jenis_bimbingan ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-square text-yellow-600 text-xl mt-1"></i>
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Status</p>
                                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                        @if($konseling->status == 'selesai') bg-green-100 text-green-800
                                        @elseif($konseling->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                                        @elseif($konseling->status == 'menunggu') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($konseling->status) }}
                                    </span>
                                    @if(is_null($konseling->catatan_id) && $konseling->status === 'selesai')
                                        <p class="text-xs text-yellow-600 font-semibold mt-1">
                                            <i class="fas fa-exclamation-circle"></i> Belum ada catatan
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Aksi -->
                            <div class="flex items-start gap-2 flex-wrap">
                                <a href="{{ route('guru.jadwal.detail', $konseling->janji_id) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-sm font-medium inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition">
                                    <i class="fas fa-eye"></i> 
                                    <span>Detail</span>
                                </a>
                                @if($konseling->status === 'selesai')
                                    @if(!is_null($konseling->catatan_id))
                                        <a href="{{ route('guru.riwayat.detail', $konseling->catatan_id) }}" 
                                           class="text-green-600 hover:text-green-900 text-sm font-medium inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 px-3 py-1 rounded transition">
                                            <i class="fas fa-clipboard-check"></i> 
                                            <span>Catatan</span>
                                        </a>
                                    @else
                                        <a href="{{ route('guru.riwayat.tambah', $konseling->janji_id) }}" 
                                           class="text-orange-600 hover:text-orange-900 text-sm font-medium inline-flex items-center gap-1 bg-orange-50 hover:bg-orange-100 px-3 py-1 rounded transition">
                                            <i class="fas fa-plus"></i> 
                                            <span>Catatan</span>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Keluhan -->
                        @if($konseling->keluhan)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Keluhan / Masalah</p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $konseling->keluhan }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($riwayatKonseling->hasPages())
                <div class="mt-6">
                    {{ $riwayatKonseling->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg font-medium">Belum ada riwayat konseling</p>
                    <p class="text-gray-500 text-sm mt-1">Siswa ini belum pernah melakukan konseling</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection