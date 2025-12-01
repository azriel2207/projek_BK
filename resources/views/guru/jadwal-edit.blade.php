@extends('layouts.guru-layout')

@section('title', 'Edit Jadwal Konseling - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Jadwal Konseling</h1>
            <p class="text-gray-600">Ubah jadwal konseling siswa</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.jadwal') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Jadwal</span>
            </a>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.jadwal.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Student Selection -->
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>Pilih Siswa
                    </label>
                    <select name="user_id" id="user_id" 
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswaList as $student)
                            <option value="{{ $student->id }}" 
                                    {{ $jadwal->user_id == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->class ?? 'Belum ada kelas' }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Konseling -->
                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>Tanggal Konseling
                    </label>
                    <input type="date" 
                           name="tanggal" 
                           id="tanggal"
                           value="{{ old('tanggal', $jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') : '') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('tanggal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div class="mb-4">
                    <label for="mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-1"></i>Waktu Mulai
                    </label>
                    <input type="time" 
                           name="mulai" 
                           id="mulai"
                           value="{{ old('mulai', $jadwal->waktu ? explode(' - ', $jadwal->waktu)[0] : '') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Selesai -->
                <div class="mb-4">
                    <label for="selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-1"></i>Waktu Selesai (Opsional)
                    </label>
                    <input type="time" 
                           name="selesai" 
                           id="selesai"
                           value="{{ old('selesai', $jadwal->waktu && str_contains($jadwal->waktu, ' - ') ? explode(' - ', $jadwal->waktu)[1] : '') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('selesai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Bimbingan -->
                <div class="mb-4">
                    <label for="jenis_bimbingan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Jenis Bimbingan
                    </label>
                    <select name="jenis_bimbingan" id="jenis_bimbingan"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Jenis Bimbingan --</option>
                        <option value="Akademik" {{ old('jenis_bimbingan', $jadwal->jenis_bimbingan) == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Karir" {{ old('jenis_bimbingan', $jadwal->jenis_bimbingan) == 'Karir' ? 'selected' : '' }}>Karir</option>
                        <option value="Personal" {{ old('jenis_bimbingan', $jadwal->jenis_bimbingan) == 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Sosial" {{ old('jenis_bimbingan', $jadwal->jenis_bimbingan) == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                    </select>
                    @error('jenis_bimbingan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keluhan / Permasalahan -->
                <div class="mb-4">
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-1"></i>Keluhan / Permasalahan
                    </label>
                    <textarea name="keluhan" 
                              id="keluhan" 
                              rows="4"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Deskripsikan keluhan atau permasalahan siswa...">{{ old('keluhan', $jadwal->keluhan) }}</textarea>
                    @error('keluhan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Status
                    </label>
                    <select name="status" id="status"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="menunggu" {{ old('status', $jadwal->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dikonfirmasi" {{ old('status', $jadwal->status) == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('guru.jadwal') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Student Info -->
        @if($selectedSiswa)
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Siswa Terpilih</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-blue-700">Nama:</span>
                    <span class="text-blue-900">{{ $selectedSiswa->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Kelas:</span>
                    <span class="text-blue-900">{{ $selectedSiswa->class ?? 'Belum ditentukan' }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Email:</span>
                    <span class="text-blue-900">{{ $selectedSiswa->email }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Status:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const tanggalInput = document.getElementById('tanggal');
    
    if (tanggalInput) {
        tanggalInput.min = today;
    }
});
</script>
@endsection