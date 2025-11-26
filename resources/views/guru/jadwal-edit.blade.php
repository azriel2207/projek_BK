@extends('layouts.app')

@section('title', 'Edit Jadwal - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Jadwal Konseling</h1>
                <p class="text-gray-600">Ubah informasi jadwal konseling</p>
            </div>
            <a href="{{ route('guru.jadwal') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Form Edit Jadwal</h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('guru.jadwal.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Siswa -->
                    <div class="form-group">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                        <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}" {{ $jadwal->user_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} - {{ $s->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" 
                               value="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>

                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                        <input type="time" name="mulai" id="mulai" 
                               value="{{ explode(' - ', $jadwal->waktu)[0] ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>

                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                        <input type="time" name="selesai" id="selesai" 
                               value="{{ explode(' - ', $jadwal->waktu)[1] ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Jenis Bimbingan -->
                    <div class="form-group">
                        <label for="jenis_bimbingan" class="block text-sm font-medium text-gray-700 mb-2">Jenis Bimbingan</label>
                        <select name="jenis_bimbingan" id="jenis_bimbingan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Jenis Bimbingan</option>
                            <option value="pribadi" {{ $jadwal->jenis_bimbingan == 'pribadi' ? 'selected' : '' }}>Bimbingan Pribadi</option>
                            <option value="belajar" {{ $jadwal->jenis_bimbingan == 'belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                            <option value="karir" {{ $jadwal->jenis_bimbingan == 'karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                            <option value="sosial" {{ $jadwal->jenis_bimbingan == 'sosial' ? 'selected' : '' }}>Bimbingan Sosial</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="menunggu" {{ $jadwal->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dikonfirmasi" {{ $jadwal->status == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="selesai" {{ $jadwal->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $jadwal->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <!-- Keluhan -->
                <div class="form-group mt-6">
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">Keluhan / Permasalahan</label>
                    <textarea name="keluhan" id="keluhan" rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Deskripsi keluhan atau permasalahan...">{{ $jadwal->keluhan }}</textarea>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('guru.jadwal') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection