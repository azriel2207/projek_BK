@extends('layouts.guru-layout')

@section('title', 'Buat Catatan - Guru BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Catatan Konseling</h1>
        <p class="text-gray-600">Berikan catatan untuk siswa tentang hasil konseling</p>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.riwayat.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden max-w-2xl">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Form Catatan Baru</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('guru.riwayat.simpan') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ old('user_id') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Catatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi" id="isi" rows="8" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isi') border-red-500 @enderror"
                              placeholder="Tuliskan catatan mengenai hasil konseling dengan siswa..." required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 justify-end border-t pt-4">
                    <a href="{{ route('guru.riwayat.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection