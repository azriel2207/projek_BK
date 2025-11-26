@extends('layouts.app')

@section('title', 'Detail Jadwal - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Jadwal Konseling</h1>
                <p class="text-gray-600">Informasi lengkap jadwal konseling</p>
            </div>
            <a href="{{ route('guru.jadwal') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Jadwal</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Siswa -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Siswa</h3>
                    
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nama Siswa</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $jadwal->nama_siswa }}</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Email</div>
                        <div class="text-lg text-gray-900">{{ $jadwal->email_siswa }}</div>
                    </div>
                </div>

                <!-- Informasi Jadwal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Jadwal</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Tanggal</div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Waktu</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $jadwal->waktu }}</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Jenis Bimbingan</div>
                        <div class="text-lg font-semibold text-gray-900 capitalize">
                            {{ $jadwal->jenis_bimbingan }}
                        </div>
                    </div>

                    @php
                        $statusColors = [
                            'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                            'dikonfirmasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check'],
                            'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                            'dibatalkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
                        ];
                        $status = $statusColors[$jadwal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question'];
                    @endphp

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Status</div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                            <i class="fas {{ $status['icon'] }} mr-2"></i>
                            {{ ucfirst($jadwal->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Keluhan -->
            @if($jadwal->keluhan)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Keluhan / Permasalahan</h3>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">{{ $jadwal->keluhan }}</p>
                </div>
            </div>
            @endif

            <!-- Catatan Konselor -->
            @if($jadwal->catatan_konselor)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Catatan Konselor</h3>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">{{ $jadwal->catatan_konselor }}</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex gap-3">
                <a href="{{ route('guru.jadwal.edit', $jadwal->id) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="fas fa-edit"></i>
                    <span>Edit Jadwal</span>
                </a>
                
                @if($jadwal->status != 'selesai' && $jadwal->status != 'dibatalkan')
                <a href="{{ route('guru.catatan.tambah', $jadwal->id) }}" 
                   class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="fas fa-notes-medical"></i>
                    <span>Tambah Catatan</span>
                </a>
                @endif

                <form action="{{ route('guru.jadwal.hapus', $jadwal->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-trash"></i>
                        <span>Hapus Jadwal</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection