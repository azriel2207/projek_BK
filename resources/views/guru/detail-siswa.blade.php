@extends('layouts.guru-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('guru.siswa-list') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Siswa</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-32"></div>
                
                <div class="px-6 pb-6">
                    <div class="flex justify-center -mt-16 mb-4">
                        <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center border-4 border-white">
                            <i class="fas fa-user text-blue-600 text-5xl"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-center text-gray-800">{{ $siswa->nama_lengkap }}</h2>
                    <p class="text-center text-gray-600 mt-1">{{ $siswa->nis }}</p>

                    <div class="mt-6 space-y-3">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->user->email }}</p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Kelas</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->kelas ?? '-' }}</p>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Wali Kelas</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->waliKelas?->name ?? 'Belum ditentukan' }}</p>
                        </div>

                        <div class="bg-orange-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Status NIS</p>
                            @if($siswa->nis_verified)
                                <p class="font-semibold text-green-600"><i class="fas fa-check-circle mr-2"></i>Terverifikasi</p>
                            @else
                                <p class="font-semibold text-yellow-600"><i class="fas fa-clock mr-2"></i>Belum Verifikasi</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Details -->
        <div class="lg:col-span-2">
            <!-- Biodata Section -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-id-card text-blue-600"></i>Data Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->nama_lengkap }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">NIS</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->nis }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800">
                            @if($siswa->tgl_lahir)
                                {{ \Carbon\Carbon::parse($siswa->tgl_lahir)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tempat Lahir</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->identity?->tempat_lahir ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kelas</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->kelas ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. Telepon</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->no_hp ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-600 mb-2">Alamat</p>
                    <p class="text-gray-800">{{ $siswa->alamat ?? '-' }}</p>
                </div>
            </div>

            <!-- Wali Kelas Info -->
            @if($siswa->waliKelas)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-tie text-green-600"></i>Informasi Wali Kelas
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-gray-600">Nama</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->waliKelas->name }}</p>
                        </div>
                        <div class="flex items-center justify-between border-t pt-4">
                            <p class="text-gray-600">Email</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->waliKelas->email }}</p>
                        </div>
                        <div class="flex items-center justify-between border-t pt-4">
                            <p class="text-gray-600">No. Telepon</p>
                            <p class="font-semibold text-gray-800">{{ $siswa->waliKelas->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-envelope text-purple-600"></i>Informasi Akun
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->user->email }}</p>
                    </div>
                    <div class="flex items-center justify-between border-t pt-4">
                        <p class="text-gray-600">Role</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($siswa->user->role) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between border-t pt-4">
                        <p class="text-gray-600">Status NIS</p>
                        @if($siswa->nis_verified)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Belum Verifikasi
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between border-t pt-4">
                        <p class="text-gray-600">Bergabung Sejak</p>
                        <p class="font-semibold text-gray-800">{{ $siswa->user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
