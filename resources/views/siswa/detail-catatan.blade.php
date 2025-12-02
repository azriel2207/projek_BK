@extends('layouts.siswa-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Catatan</h1>
        <p class="text-gray-600">Catatan dari guru BK mengenai konseling Anda</p>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.catatan.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Catatan</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Dari Guru BK</label>
                    <p class="text-gray-900 font-semibold">{{ $catatan->guru_bk }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Konseling</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status</label>
                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-medium mt-1">
                        <i class="fas fa-check-circle mr-1"></i>Diterima
                    </span>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>Dibuat: {{ \Carbon\Carbon::parse($catatan->created_at)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Catatan Content -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Isi Catatan</h2>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $catatan->isi }}</p>
                </div>
            </div>

            <!-- Related Consultation Info -->
            @if($janji)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Konseling Terkait</h2>
                
                <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between">
                        <label class="text-sm font-medium text-gray-600">Jenis Bimbingan</label>
                        <span class="text-gray-900">{{ ucfirst($janji->jenis_bimbingan ?? '-') }}</span>
                    </div>
                    
                    <div class="flex justify-between border-t pt-3">
                        <label class="text-sm font-medium text-gray-600">Tanggal Konseling</label>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($janji->tanggal)->format('d M Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between border-t pt-3">
                        <label class="text-sm font-medium text-gray-600">Waktu</label>
                        <span class="text-gray-900">{{ $janji->waktu ?? '-' }}</span>
                    </div>
                    
                    @if($janji->keluhan)
                    <div class="border-t pt-3">
                        <label class="text-sm font-medium text-gray-600 block mb-2">Keluhan/Masalah</label>
                        <p class="text-gray-800">{{ $janji->keluhan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
