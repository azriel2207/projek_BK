@extends('layouts.app')

@section('title', 'Edit Kelas Siswa - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Kelas Siswa</h1>
            <p class="text-gray-600">Ubah kelas untuk siswa</p>
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Siswa</h3>
            
            <div class="space-y-3">
                <div>
                    <div class="text-sm font-medium text-gray-500">Nama Siswa</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $siswa->name }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Email</div>
                    <div class="text-gray-900">{{ $siswa->email }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Kelas Saat Ini</div>
                    <div class="text-lg text-gray-900 font-semibold">
                        {{ $siswa->class ?? 'Belum ditentukan' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Kelas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.siswa.kelas.update', $siswa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>Pilih Kelas Baru
                    </label>
                    <select name="class" id="class" 
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" 
                                    {{ $siswa->class == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('class')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('guru.siswa.detail', $siswa->id) }}" 
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

        <!-- Quick Actions -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Tips:</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Pastikan kelas sesuai dengan struktur kelas di sekolah</li>
                <li>• Perubahan kelas akan mempengaruhi filter dan laporan</li>
                <li>• Siswa dapat dipindahkan ke kelas lain kapan saja</li>
            </ul>
        </div>
    </div>
</div>
@endsection