@extends('layouts.app')

@section('title', 'Detail Catatan Konseling')

@section('content')
<div class="container mx-auto px-6 py-6">

    {{-- Judul Halaman --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Catatan Konseling</h1>
        <p class="text-gray-500">Informasi lengkap dari catatan konseling ini</p>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('guru.riwayat.index') }}"
           class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
            ← Kembali
        </a>
    </div>

    {{-- Card Detail --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <h3 class="text-lg font-semibold mb-2">Informasi Siswa</h3>
                <p><strong>Nama:</strong> {{ $catatan->user->name ?? '-' }}</p>
                <p><strong>ID Siswa:</strong> {{ $catatan->user_id }}</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2">Informasi Konseling</h3>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</p>
                <p><strong>Waktu:</strong> {{ $catatan->waktu }}</p>
                <p><strong>Jenis Bimbingan:</strong> {{ $catatan->jenis_bimbingan }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                        {{ ucfirst($catatan->status) }}
                    </span>
                </p>
            </div>
        </div>

        <hr class="my-6">

        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Keluhan / Masalah</h3>
            <p class="text-gray-700">{{ $catatan->keluhan ?? '-' }}</p>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Catatan Konselor</h3>
            <p class="text-gray-700">{{ $catatan->catatan_konselor ?: 'Tidak ada catatan.' }}</p>
        </div>

        @if($catatan->keterangan)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Keterangan Tambahan</h3>
            <p class="text-gray-700">{{ $catatan->keterangan }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
