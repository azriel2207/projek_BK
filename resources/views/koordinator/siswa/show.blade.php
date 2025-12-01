@extends('layouts.koordinator-layout')

@section('title', 'Detail Siswa')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Siswa</h1>
                <a href="{{ route('koordinator.siswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Data Siswa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Pribadi -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Pribadi</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->nama_lengkap ?? $siswa->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">NIS</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->nis ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Lahir</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->format('d/m/Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Kelas</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->kelas ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Informasi Kontak -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Kontak</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">No HP</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->no_hp ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Alamat</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $siswa->alamat ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Role</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ $siswa->role }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('koordinator.siswa.edit', $siswa->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                
                @if($siswa->role === 'siswa')
                <a href="{{ route('koordinator.siswa.upgrade-form', $siswa->id) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition"
                   onclick="return confirm('Upgrade {{ $siswa->nama_lengkap ?? $siswa->name }} menjadi Guru BK?')">
                    <i class="fas fa-user-graduate mr-2"></i>Upgrade ke Guru BK
                </a>
                @endif

                <form action="{{ route('koordinator.siswa.destroy', $siswa->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition"
                            onclick="return confirm('Hapus siswa {{ $siswa->nama_lengkap ?? $siswa->name }}?')">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection