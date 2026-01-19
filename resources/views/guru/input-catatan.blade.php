@extends('layouts.guru-layout')

@section('title', 'Input Catatan Konseling - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center text-sm text-gray-600 space-x-2">
        <a href="{{ route('guru.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">Input Catatan Konseling</span>
    </div>

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-md p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Input Catatan Konseling</h1>
                <p class="text-blue-100">Berikan catatan dan rekomendasi untuk siswa setelah konseling</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <!-- Informasi Siswa -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Informasi Siswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <label class="text-xs font-bold text-blue-700 uppercase">Nama Siswa</label>
                            <p class="text-gray-800 font-semibold mt-2">{{ $janji->name }}</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <label class="text-xs font-bold text-green-700 uppercase">Jenis Bimbingan</label>
                            <p class="text-gray-800 font-semibold mt-2 capitalize">{{ str_replace('_', ' ', $janji->jenis_bimbingan) }}</p>
                        </div>
                        <div class="p-4 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                            <label class="text-xs font-bold text-amber-700 uppercase">Tanggal Konseling</label>
                            <p class="text-gray-800 font-semibold mt-2">{{ \Carbon\Carbon::parse($janji->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                            <label class="text-xs font-bold text-purple-700 uppercase">Waktu Konseling</label>
                            <p class="text-gray-800 font-semibold mt-2">{{ $janji->waktu }}</p>
                        </div>
                    </div>
                </div>

                <!-- Keluhan -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-comment-dots text-orange-600 mr-2"></i>Keluhan / Permasalahan Siswa
                    </h3>
                    <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-gray-400">
                        <p class="text-gray-700 leading-relaxed">{{ $janji->keluhan }}</p>
                    </div>
                </div>

                <!-- Form Input Catatan -->
                <form action="{{ route('guru.catatan.save', $janji->id) }}" method="POST" id="catatanForm">
                    @csrf
                    <input type="hidden" name="janji_id" value="{{ $janji->id }}">

                    <!-- Catatan Hasil Konseling -->
                    <div class="mb-6">
                        <label for="isi_catatan" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-pen-fancy text-blue-600 mr-2"></i>Catatan Hasil Konseling <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="isi_catatan" 
                            name="isi_catatan" 
                            rows="6"
                            placeholder="Tuliskan ringkasan hasil konseling, proses konseling, insight yang didapat siswa, dll..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            @error('isi_catatan') is-invalid @enderror>{{ old('isi_catatan') }}</textarea>
                        @error('isi_catatan')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Minimal 10 karakter. Jelaskan hasil konseling dengan detail.
                        </p>
                    </div>

                    <!-- Rekomendasi -->
                    <div class="mb-6">
                        <label for="rekomendasi" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>Rekomendasi / Saran Tindak Lanjut <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="rekomendasi" 
                            name="rekomendasi" 
                            rows="6"
                            placeholder="Berikan rekomendasi/saran untuk siswa, misalnya: rujukan ke psikolog, perubahan gaya belajar, komunikasi dengan orang tua, dll..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"
                            @error('rekomendasi') is-invalid @enderror>{{ old('rekomendasi') }}</textarea>
                        @error('rekomendasi')
                            <p class="text-red-600 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Minimal 10 karakter. Rekomendasi konkret untuk tindak lanjut.
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg mb-8">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Catatan Penting:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Catatan dan rekomendasi akan dilihat oleh siswa</li>
                                    <li>Pastikan isi jelas, profesional, dan konstruktif</li>
                                    <li>Setelah menyimpan, Anda dapat "Tandai Selesai" untuk konseling ini</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button 
                            type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>Simpan Catatan
                        </button>
                        <a 
                            href="{{ route('guru.dashboard') }}"
                            class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Side Panel - Status & Timeline -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tasks text-green-600 mr-2"></i>Status Proses
                </h3>
                
                <!-- Timeline -->
                <div class="space-y-4">
                    <!-- Step 1: Created -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-600 text-white">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">Janji Dibuat</p>
                            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($janji->created_at)->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="flex">
                        <div class="flex-shrink-0 mt-2">
                            <div class="flex items-center justify-center h-6 w-8">
                                <div class="h-full w-1 bg-green-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Confirmed -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-600 text-white">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">Dikonfirmasi Guru BK</p>
                            <p class="text-xs text-gray-600">Sudah terjadwal</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="flex">
                        <div class="flex-shrink-0 mt-2">
                            <div class="flex items-center justify-center h-6 w-8">
                                <div class="h-full w-1 bg-blue-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Catatan (Current) -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white animate-pulse">
                                <i class="fas fa-pen-fancy text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-blue-800">Input Catatan <span class="text-xs bg-blue-100 px-2 py-1 rounded text-blue-700 ml-2">Sekarang</span></p>
                            <p class="text-xs text-gray-600">Form ini</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="flex">
                        <div class="flex-shrink-0 mt-2">
                            <div class="flex items-center justify-center h-6 w-8">
                                <div class="h-full w-1 bg-gray-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Selesai -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-300 text-gray-600">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-600">Tandai Selesai</p>
                            <p class="text-xs text-gray-500">Setelah catatan disimpan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-md p-6 border-l-4 border-amber-500">
                <h4 class="font-bold text-amber-900 mb-3 flex items-center">
                    <i class="fas fa-bell mr-2"></i>Reminder
                </h4>
                <ul class="text-sm text-amber-900 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-arrow-right mr-2 mt-1 flex-shrink-0"></i>
                        <span>Isi catatan dengan detail</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-arrow-right mr-2 mt-1 flex-shrink-0"></i>
                        <span>Berikan rekomendasi konkret</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-arrow-right mr-2 mt-1 flex-shrink-0"></i>
                        <span>Klik "Tandai Selesai" nanti</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .container {
        animation: slideIn 0.3s ease-out;
    }
</style>
@endsection
