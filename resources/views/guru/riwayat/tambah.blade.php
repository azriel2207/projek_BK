@extends('layouts.guru-layout')

@section('title', 'Tambah Catatan - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Catatan Konseling</h1>
            <p class="text-gray-600">Berikan catatan/jawaban atas konseling yang telah dilakukan</p>
        </div>

        <!-- Info Janji -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Siswa</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $janji->user->name ?? 'Siswa' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $janji->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Bimbingan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($janji->jenis_bimbingan) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Konseling</p>
                    <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($janji->tanggal)->format('d M Y') }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p class="text-sm text-gray-600">Keluhan Siswa</p>
                <p class="text-gray-800 mt-2">{{ $janji->keluhan }}</p>
            </div>
        </div>

        <!-- Form Tambah Catatan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.riwayat.simpan') }}" method="POST">
                @csrf

                <!-- Hidden Input untuk janji_id -->
                <input type="hidden" name="janji_id" value="{{ $janji->id }}">

                <div class="space-y-6">
                    <!-- Catatan Konselor -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-comment-dots text-blue-600 mr-2"></i>Catatan Konseling *
                        </label>
                        <textarea 
                            name="isi_catatan"
                            rows="8" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tuliskan catatan konseling Anda tentang hasil diskusi dengan siswa, rekomendasi, dan tindak lanjut..."
                            required
                        >{{ old('isi_catatan') }}</textarea>
                        @error('isi_catatan')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekomendasi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>Rekomendasi
                        </label>
                        <textarea 
                            name="rekomendasi"
                            rows="4" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Berikan rekomendasi atau saran untuk tindak lanjut..."
                        >{{ old('rekomendasi') }}</textarea>
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
                            <span>Simpan Catatan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
