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
                    
                    <form id="formKonsultasiKarir" method="POST" action="{{ route('siswa.janji-konseling.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="jenis_bimbingan" value="karir">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Topik Konsultasi</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Pilih topik konsultasi</option>
                                <option value="pemilihan_jurusan">Pemilihan Jurusan Kuliah</option>
                                <option value="minat_bakat">Identifikasi Minat & Bakat</option>
                                <option value="perencanaan_karir">Perencanaan Karir Jangka Panjang</option>
                                <option value="persiapan_kerja">Persiapan Dunia Kerja</option>
                                <option value="magang">Rekomendasi Magang</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru BK (Opsional)</label>
                            <select name="guru_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Pilih Guru BK (Opsional)</option>
                                @if(isset($gurus) && $gurus->count() > 0)
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal yang diinginkan</label>
                            <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                            <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <option value="">Pilih Waktu</option>
                                <option value="08:00 - 09:00">08:00 - 09:00</option>
                                <option value="09:00 - 10:00">09:00 - 10:00</option>
                                <option value="10:00 - 11:00">10:00 - 11:00</option>
                                <option value="13:00 - 14:00">13:00 - 14:00</option>
                                <option value="14:00 - 15:00">14:00 - 15:00</option>
                                <option value="15:00 - 16:00">15:00 - 16:00</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan/Keluhan Spesifik</label>
                            <textarea name="keluhan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Jelaskan pertanyaan atau keluhan spesifik mengenai karir..." required></textarea>
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
                                    <div class="bg-blue-100 p-3 rounded-full">
                                        <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
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
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        function ajukanKonsultasiKarir() {
            alert('Konsultasi karir berhasil diajukan!\n\nGuru BK akan menghubungi Anda untuk sesi konsultasi.');
            document.getElementById('formKonsultasiKarir').reset();
        }
    </script>
</body>
</html>