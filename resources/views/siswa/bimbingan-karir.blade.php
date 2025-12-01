@extends('layouts.siswa-layout')

@section('title', 'Bimbingan Karir - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl shadow-sm p-6 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold mb-2">Konsultasi Karir 💼</h1>
                <p class="text-green-100">Diskusikan masa depan karir Anda dengan ahli bimbingan karir</p>
            </div>
            <div class="text-4xl">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
    </div>

    <!-- Konsultasi Karir Baru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Form Konsultasi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-comments mr-2 text-green-600"></i>Ajukan Konsultasi Karir
            </h3>
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded p-3 mb-4">
                    <ul class="text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded p-3 mb-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif
            
            <form id="formKonsultasiKarir" method="POST" action="{{ route('siswa.janji-konseling.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="jenis_bimbingan" value="karir">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Topik Konsultasi</label>
                    <select name="topik_karir" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('topik_karir') border-red-500 @enderror">
                        <option value="">Pilih topik konsultasi</option>
                        <option value="pemilihan_jurusan" @selected(old('topik_karir') == 'pemilihan_jurusan')>Pemilihan Jurusan Kuliah</option>
                        <option value="minat_bakat" @selected(old('topik_karir') == 'minat_bakat')>Identifikasi Minat & Bakat</option>
                        <option value="perencanaan_karir" @selected(old('topik_karir') == 'perencanaan_karir')>Perencanaan Karir Jangka Panjang</option>
                        <option value="persiapan_kerja" @selected(old('topik_karir') == 'persiapan_kerja')>Persiapan Dunia Kerja</option>
                        <option value="magang" @selected(old('topik_karir') == 'magang')>Rekomendasi Magang</option>
                        <option value="lainnya" @selected(old('topik_karir') == 'lainnya')>Lainnya</option>
                    </select>
                    @error('topik_karir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru BK (Opsional)</label>
                    <select name="guru_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Guru BK (Opsional)</option>
                        @if(isset($gurus) && $gurus->count() > 0)
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" @selected(old('guru_id') == $guru->id)>{{ $guru->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal yang diinginkan</label>
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                    <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('waktu') border-red-500 @enderror" required>
                        <option value="">Pilih Waktu</option>
                        <option value="08:00 - 09:00" @selected(old('waktu') == '08:00 - 09:00')>08:00 - 09:00</option>
                        <option value="09:00 - 10:00" @selected(old('waktu') == '09:00 - 10:00')>09:00 - 10:00</option>
                        <option value="10:00 - 11:00" @selected(old('waktu') == '10:00 - 11:00')>10:00 - 11:00</option>
                        <option value="13:00 - 14:00" @selected(old('waktu') == '13:00 - 14:00')>13:00 - 14:00</option>
                        <option value="14:00 - 15:00" @selected(old('waktu') == '14:00 - 15:00')>14:00 - 15:00</option>
                        <option value="15:00 - 16:00" @selected(old('waktu') == '15:00 - 16:00')>15:00 - 16:00</option>
                    </select>
                    @error('waktu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan/Keluhan Spesifik</label>
                    <textarea name="keluhan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('keluhan') border-red-500 @enderror" placeholder="Jelaskan pertanyaan atau keluhan spesifik mengenai karir..." required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Konsultasi Karir
                </button>
            </form>
        </div>

        <!-- Spesialis Karir -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-tie mr-2 text-blue-600"></i>Spesialis Karir Tersedia
            </h3>
            
            <div class="space-y-4">
                @if(isset($gurus) && $gurus->count() > 0)
                    @foreach($gurus as $g)
                        <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg">
                            <div class="bg-blue-100 p-3 rounded-full flex-shrink-0">
                                <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $g->name }}</p>
                                <p class="text-sm text-gray-600">Guru BK</p>
                                <p class="text-xs text-blue-600">✅ Tersedia untuk konsultasi</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-sm text-gray-600">Belum ada guru BK terdaftar.</div>
                @endif
            </div>
            
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                    <i class="fas fa-star mr-2"></i>Manfaat Konsultasi Karir
                </h4>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Pemahaman minat dan bakat yang lebih baik</li>
                    <li>• Rekomendasi jurusan kuliah yang tepat</li>
                    <li>• Perencanaan karir jangka panjang</li>
                    <li>• Persiapan memasuki dunia kerja</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    });
</script>
@endsection