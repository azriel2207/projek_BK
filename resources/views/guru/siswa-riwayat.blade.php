@extends('layouts.guru-layout')

@section('title', 'Riwayat Konseling Siswa - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Konseling Lengkap</h1>
            <p class="text-gray-600">Semua riwayat konseling {{ $siswa->name }}</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.siswa.detail', $siswa->id) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Detail Siswa</span>
            </a>
        </div>

        <!-- Student Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $siswa->name }}</h2>
                    <p class="text-gray-600">{{ $siswa->email }} • {{ $siswa->class ?? 'Belum ada kelas' }}</p>
                </div>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    Total: {{ $riwayatKonseling->total() }} konseling
                </span>
            </div>
        </div>

        <!-- Riwayat Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Konseling</h3>
            </div>

            @if($riwayatKonseling->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal & Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keluhan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riwayatKonseling as $konseling)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($konseling->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $konseling->waktu }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($konseling->jenis_bimbingan == 'Akademik') bg-blue-100 text-blue-800
                                        @elseif($konseling->jenis_bimbingan == 'Karir') bg-green-100 text-green-800
                                        @elseif($konseling->jenis_bimbingan == 'Personal') bg-purple-100 text-purple-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $konseling->jenis_bimbingan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $konseling->keluhan ?: '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('guru.jadwal.detail', $konseling->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    @if($konseling->status == 'selesai' && $konseling->catatan_konselor)
                                    <a href="{{ route('guru.riwayat.detail', $konseling->id) }}" 
                                       class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-clipboard-check"></i> Catatan
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($riwayatKonseling->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $riwayatKonseling->links() }}
                </div>
                @endif
            @else
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg">Belum ada riwayat konseling</p>
                    <p class="text-gray-400 text-sm mt-1">Siswa ini belum pernah melakukan konseling</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection