@extends('layouts.app')

@section('title', 'Catatan Konseling - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">📝 Catatan Konseling</h1>
        <p class="text-gray-600">Kelola catatan dan progress konseling siswa</p>
    </div>

    <!-- Navigation - PASTIKAN ROUTE BENAR -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        
        <!-- GUNAKAN ROUTE YANG BENAR -->
        <a href="{{ route('guru.catatan.buat') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-plus"></i>
            <span>Buat Catatan Baru</span>
        </a>
        
        <!-- GUNAKAN ROUTE YANG BENAR -->
        <a href="{{ route('guru.catatan.template') }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-copy"></i>
            <span>Template Catatan</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Catatan Konseling</h2>
            <p class="text-sm text-gray-600">Total {{ $catatan->count() }} catatan</p>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($catatan as $item)
            <div class="p-6 hover:bg-gray-50 transition duration-150">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->nama_siswa }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->kelas }} - {{ ucfirst($item->jenis_konseling) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">{{ $item->tanggal->format('d M Y') }}</span>
                        <div class="flex space-x-2 mt-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($item->status == 'selesai') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $item->keluhan }}</p>
                </div>
                
                @if($item->catatan_konselor)
                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-800 mb-1">Catatan Konselor:</p>
                    <p class="text-sm text-gray-700">{{ $item->catatan_konselor }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                <p class="text-lg">Belum ada catatan konseling</p>
                <p class="text-sm mt-2">Mulai dengan membuat catatan untuk sesi konseling yang telah dilakukan</p>
                
                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                    <!-- GUNAKAN ROUTE YANG BENAR -->
                    <a href="{{ route('guru.catatan.buat') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Buat Catatan Baru</span>
                    </a>
                    <!-- GUNAKAN ROUTE YANG BENAR -->
                    <a href="{{ route('guru.catatan.template') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition duration-200">
                        <i class="fas fa-copy"></i>
                        <span>Gunakan Template</span>
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection