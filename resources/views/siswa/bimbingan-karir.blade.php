<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Karir - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { transition: all 0.3s ease; }
        .main-content { margin-left: 16rem; }
        @media (max-width: 768px) {
            .sidebar { margin-left: -16rem; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-purple-700 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 bg-purple-600 border-l-4 border-yellow-400">
                <i class="fas fa-briefcase mr-3"></i>Bimbingan Karir
            </a>
            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-user-cog mr-3"></i>Profile Settings
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-purple-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-3 text-red-300 hover:text-red-100 transition">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center p-4">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Konsultasi Karir</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
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

            <!-- Quick Assessment -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-check mr-2 text-purple-600"></i>Assessment Cepat Minat Karir
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 mb-4">Isi assessment singkat untuk membantu Guru BK memahami minat karir Anda:</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bidang yang paling diminati?</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih bidang</option>
                                    <option value="teknologi">Teknologi & IT</option>
                                    <option value="kesehatan">Kesehatan & Medis</option>
                                    <option value="pendidikan">Pendidikan & Pengajaran</option>
                                    <option value="bisnis">Bisnis & Manajemen</option>
                                    <option value="seni">Seni & Kreatif</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Skill yang paling dikuasai?</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih skill</option>
                                    <option value="analitis">Analitis & Logika</option>
                                    <option value="komunikasi">Komunikasi</option>
                                    <option value="kreatif">Kreatif & Inovatif</option>
                                    <option value="organisasi">Organisasi</option>
                                    <option value="teknis">Teknis</option>
                                </select>
                            </div>
                            
                            <button onclick="simpanAssessment()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                                <i class="fas fa-save mr-2"></i>Simpan Assessment
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-800 mb-3">Hasil Assessment Sementara</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Kecocokan Teknologi:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-sm font-medium">85%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Kecocokan Pendidikan:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                                <span class="text-sm font-medium">70%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Kecocokan Bisnis:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                                <span class="text-sm font-medium">60%</span>
                            </div>
                        </div>
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

            <!-- Riwayat Konsultasi Karir -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-purple-600"></i>Riwayat Konsultasi Karir
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Selesai
                                </span>
                                <span class="text-sm font-medium text-gray-800">Pemilihan Jurusan Kuliah</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Konsultasi pemilihan jurusan TI vs Kedokteran</p>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>10 Okt 2024
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-user-tie mr-2"></i>Bpk. Ahmad, M.Pd
                                </span>
                            </div>
                        </div>
                        <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </button>
                    </div>
                    
                    <div class="text-center py-8">
                        <i class="fas fa-briefcase text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">Belum ada riwayat konsultasi karir</p>
                        <p class="text-gray-400 text-sm mt-1">Ajukan konsultasi karir pertama Anda!</p>
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

        function simpanAssessment() {
            alert('Assessment minat karir berhasil disimpan!\n\nHasil akan digunakan dalam konsultasi dengan Guru BK.');
        }

        function ajukanKonsultasiKarir() {
            alert('Konsultasi karir berhasil diajukan!\n\nGuru BK akan menghubungi Anda untuk sesi konsultasi.');
            document.getElementById('formKonsultasiKarir').reset();
        }
    </script>
</body>
</html>