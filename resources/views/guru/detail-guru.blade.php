@extends('layouts.guru-layout')

@section('title', 'Detail Guru - ' . $guru->name)

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('guru.guru') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar Guru</span>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $guru->name }}</h1>
        <p class="text-gray-600 mt-1">Lihat detail informasi guru</p>
    </div>

    <!-- Profile Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Guru</h2>
                
                <div class="space-y-4">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ $guru->name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ $guru->email }}</p>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">{{ $guru->phone ?? 'Tidak diisi' }}</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $guru->role === 'guru_bk' ? 'Guru BK' : 'Guru' }}
                            </span>
                        </p>
                    </div>

                    <!-- Tanggal Bergabung -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg text-gray-900">
                            {{ \Carbon\Carbon::parse($guru->created_at)->format('d F Y, H:i') }}
                        </p>
                    </div>

                    <!-- Status Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Email</label>
                        <p class="px-4 py-3 bg-gray-50 rounded-lg">
                            @if($guru->email_verified_at)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="md:col-span-1">
            <!-- Avatar Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="text-center">
                    <div class="h-24 w-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-tie text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $guru->name }}</h3>
                    <p class="text-sm text-gray-600">ID: {{ $guru->id }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        @if($guru->role === 'guru_bk')
                            <span class="text-blue-600 font-medium">Guru BK</span>
                        @else
                            <span class="text-gray-600 font-medium">Guru</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="font-medium">
                            @if($guru->email_verified_at)
                                ✓ Ya
                            @else
                                ✗ Tidak
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t pt-3">
                        <span class="text-gray-600">Bergabung:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($guru->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex gap-3">
        <a href="{{ route('guru.guru') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>
@endsection
