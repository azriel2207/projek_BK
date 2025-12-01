@extends('layouts.guru-layout')

@section('title', 'Tambah Catatan - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Catatan Konseling</h1>
                <p class="text-gray-600">Tambahkan catatan untuk sesi konseling</p>
            </div>
            <a href="{{ route('guru.jadwal') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Jadwal -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jadwal</h3>
            
            <div class="space-y-3">
                <div>
                    <div class="text-sm font-medium text-gray-500">Siswa</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $jadwal->nama_siswa }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Tanggal & Waktu</div>
                    <div class="text-lg text-gray-900">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} - {{ $jadwal->waktu }}
                    </div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Jenis Bimbingan</div>
                    <div class="text-lg text-gray-900 capitalize">{{ $jadwal->jenis_bimbingan }}</div>
                </div>

                @if($jadwal->keluhan)
                <div>
                    <div class="text-sm font-medium text-gray-500">Keluhan</div>
                    <div class="text-sm text-gray-700 mt-1 p-3 bg-gray-50 rounded-lg">
                        {{ $jadwal->keluhan }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Catatan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Form Catatan Konseling</h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('guru.riwayat.simpan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $jadwal->user_id ?? '' }}">
                        <input type="hidden" name="janji_id" value="{{ $jadwal->id ?? '' }}">
                        <input type="hidden" name="tanggal" value="{{ $jadwal->tanggal ?? '' }}">
                        
                        <div class="form-group">
                            <label for="catatan_konselor" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Konseling <span class="text-red-500">*</span>
                            </label>
                            <textarea name="catatan_konselor" id="catatan_konselor" rows="10" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Tuliskan catatan hasil konseling, observasi, rekomendasi, atau tindak lanjut yang diperlukan..."
                                      required></textarea>
                            <p class="text-sm text-gray-500 mt-1">Minimal 10 karakter</p>
                        </div>

                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Perhatian</p>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Dengan menyimpan catatan ini, status jadwal akan otomatis berubah menjadi <strong>"Selesai"</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2 transition">
                                <i class="fas fa-save"></i>
                                <span>Simpan Catatan</span>
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
    </div>
</div>
@endsection