@extends('layouts.guru-layout')

@section('title', 'Tandai Konseling Selesai - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tandai Konseling Selesai</h1>
            <p class="text-gray-600">Konfirmasi bahwa sesi konseling telah selesai</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.jadwal') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Jadwal</span>
            </a>
        </div>

        <!-- Alert: Catatan Status -->
        @php
            $hasCatatan = DB::table('catatan')->where('janji_id', $jadwal->id)->exists();
        @endphp

        @if(!$hasCatatan)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 px-4 py-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3 flex-shrink-0"></i>
                    <div>
                        <h3 class="font-bold mb-1">⚠️ Catatan Belum Diinput!</h3>
                        <p class="text-sm mb-3">Anda belum menginputkan catatan hasil konseling untuk siswa ini. Silakan input catatan terlebih dahulu sebelum menandai konseling sebagai selesai.</p>
                        <a href="{{ route('guru.catatan.input', $jadwal->id) }}" 
                           class="inline-flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-edit"></i>
                            Input Catatan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-1 mr-3 flex-shrink-0"></i>
                    <div>
                        <h3 class="font-bold mb-1">✓ Catatan Sudah Diinput</h3>
                        <p class="text-sm">Catatan hasil konseling sudah tersimpan. Sekarang Anda dapat menandai konseling ini sebagai selesai.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Jadwal Info Card -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl shadow-md p-6 mb-6 border-l-4 border-blue-500">
            <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Informasi Jadwal Konseling
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-medium">Siswa</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        <i class="fas fa-user mr-2 text-blue-600"></i>{{ $jadwal->user->name ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-medium">Tanggal Konseling</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        <i class="fas fa-calendar mr-2 text-green-600"></i>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}
                    </p>
                </div>
                
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-medium">Waktu Konseling</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        <i class="fas fa-clock mr-2 text-orange-600"></i>{{ $jadwal->waktu }}
                    </p>
                </div>
                
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-medium">Jenis Bimbingan</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        <i class="fas fa-tag mr-2 text-purple-600"></i>{{ ucfirst($jadwal->jenis_bimbingan) }}
                    </p>
                </div>
            </div>

            <!-- Keluhan -->
            <div class="bg-white rounded-lg p-4 mt-4">
                <p class="text-sm text-gray-600 font-medium mb-2">Keluhan / Permasalahan</p>
                <p class="text-gray-800">{{ $jadwal->keluhan }}</p>
            </div>
        </div>

        <!-- Form Tandai Selesai -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 {{ !$hasCatatan ? 'opacity-75' : '' }}">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>Konfirmasi Penyelesaian
            </h3>

            <form action="{{ route('guru.jadwal.update', $jadwal->id) }}" method="POST" {{ !$hasCatatan ? 'onsubmit="return false;"' : '' }}>
                @csrf
                @method('PUT')

                <!-- Hidden status field -->
                <input type="hidden" name="status" value="selesai">
                <input type="hidden" name="user_id" value="{{ $jadwal->user_id }}">
                <input type="hidden" name="tanggal" value="{{ $jadwal->tanggal }}">
                <input type="hidden" name="mulai" value="{{ explode(' - ', $jadwal->waktu)[0] }}">
                <input type="hidden" name="selesai" value="{{ str_contains($jadwal->waktu, ' - ') ? explode(' - ', $jadwal->waktu)[1] : '' }}">
                <input type="hidden" name="jenis_bimbingan" value="{{ $jadwal->jenis_bimbingan }}">
                <input type="hidden" name="keluhan" value="{{ $jadwal->keluhan }}">

                <!-- Catatan Setelah Konseling (readonly jika catatan belum ada) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-clipboard mr-2 text-blue-600"></i>Status Catatan Konseling
                    </label>
                    <div class="relative">
                        @if($hasCatatan)
                            <div class="p-4 bg-green-50 border border-green-300 rounded-lg">
                                <p class="text-sm text-green-800 flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    Catatan hasil konseling sudah diinput dan tersimpan
                                </p>
                            </div>
                        @else
                            <div class="p-4 bg-red-50 border border-red-300 rounded-lg">
                                <p class="text-sm text-red-800 flex items-center">
                                    <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                    Catatan belum diinput
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Konfirmasi Checkbox -->
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-start cursor-pointer {{ !$hasCatatan ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="checkbox" name="confirm_completion" value="1" 
                               class="mt-1 rounded border-gray-300 text-green-600 focus:ring-2 focus:ring-green-500"
                               {{ !$hasCatatan ? 'disabled' : 'required' }}>
                        <span class="ml-3 text-sm text-gray-800">
                            <strong>Saya konfirmasi</strong> bahwa sesi konseling dengan 
                            <strong>{{ $jadwal->user->name ?? 'siswa' }}</strong> 
                            telah selesai, catatan sudah diinput, dan status akan berubah menjadi "Selesai"
                        </span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('guru.jadwal') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition font-medium {{ !$hasCatatan ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$hasCatatan ? 'disabled' : '' }}>
                        <i class="fas fa-check-circle"></i>
                        <span>Tandai Selesai</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-900 flex items-start">
                <i class="fas fa-lightbulb mr-2 text-blue-600 mt-0.5 flex-shrink-0"></i>
                <span><strong>Workflow:</strong> 
                    @if(!$hasCatatan)
                        1️⃣ Input catatan → 2️⃣ Tandai Selesai. Silakan input catatan terlebih dahulu.
                    @else
                        Catatan sudah diinput. Klik "Tandai Selesai" untuk menyelesaikan konseling ini.
                    @endif
                </span>
            </p>
        </div>
    </div>
</div>
@endsection
