@extends('layouts.guru-layout')

@section('title', 'Edit Catatan - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Catatan Konseling</h1>
            <p class="text-gray-600">Perbarui catatan konseling untuk siswa</p>
        </div>

        <!-- Info Siswa -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Siswa</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $catatan->nama_siswa ?? 'Siswa' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">ID Siswa</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $catatan->user_id }}</p>
                </div>
            </div>
        </div>

        <!-- Form Edit Catatan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guru.riwayat.update', $catatan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Tanggal Konseling -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Tanggal Konseling *
                        </label>
                        <input 
                            type="date"
                            name="tanggal"
                            value="{{ old('tanggal', $catatan->tanggal) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('tanggal')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Konseling -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-notebook text-blue-600 mr-2"></i>Catatan Konseling *
                        </label>
                        <div class="relative">
                            <textarea 
                                name="isi_catatan"
                                rows="6" 
                                class="w-full border-2 border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-normal text-base resize-vertical bg-blue-50"
                                placeholder="Tuliskan catatan konseling Anda dengan detail mengenai hasil diskusi dan analisis..."
                                required
                            >@php
                                $parts = explode('--- REKOMENDASI ---', old('isi_catatan', $catatan->isi) ?? '');
                                echo trim($parts[0]);
                            @endphp</textarea>
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                <span>Fokus pada hasil diskusi dan analisis masalah siswa</span>
                            </div>
                        </div>
                        @error('isi_catatan')
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekomendasi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>Rekomendasi
                        </label>
                        <div class="relative">
                            <textarea 
                                name="rekomendasi"
                                rows="4" 
                                class="w-full border-2 border-yellow-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 font-normal text-base resize-vertical bg-yellow-50"
                                placeholder="Berikan rekomendasi atau saran untuk tindak lanjut siswa..."
                            >@php
                                if(count($parts) > 1) {
                                    echo trim($parts[1]);
                                }
                            @endphp</textarea>
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-lightbulb text-yellow-600 mr-1"></i>
                                <span>Saran konkrit untuk pengembangan siswa lebih lanjut</span>
                            </div>
                        </div>
                        @error('rekomendasi')
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('guru.riwayat.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2 ml-auto">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
