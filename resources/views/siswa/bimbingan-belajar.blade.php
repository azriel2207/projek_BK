<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Belajar - Sistem BK</title>
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
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 bg-purple-600 border-l-4 border-yellow-400">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Konsultasi Belajar</h2>
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
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Konsultasi Belajar 🎓</h1>
                        <p class="text-blue-100">Konsultasi langsung dengan Guru BK untuk masalah belajar Anda</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Buat Konsultasi Baru -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-plus-circle mr-2 text-green-600"></i>Konsultasi Baru
                    </h3>
                    <p class="text-gray-600 mb-4">Ajukan konsultasi untuk masalah belajar yang sedang dihadapi</p>
                    
                    <form id="formKonsultasiBelajar" method="POST" action="{{ route('siswa.janji-konseling.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="jenis_bimbingan" value="belajar">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Masalah Belajar</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih jenis masalah</option>
                                <option value="kesulitan_materi">Kesulitan Memahami Materi</option>
                                <option value="manajemen_waktu">Manajemen Waktu Belajar</option>
                                <option value="motivasi">Kurang Motivasi Belajar</option>
                                <option value="konsentrasi">Sulit Berkonsentrasi</option>
                                <option value="ujian">Persiapan Ujian</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru BK (Opsional)</label>
                            <select name="guru_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                            <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                            <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah (masukkan juga mata pelajaran jika relevan)</label>
                            <textarea name="keluhan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan secara detail masalah belajar yang dihadapi..." required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Konsultasi
                        </button>
                    </form>
                </div>

                <!-- Guru BK Tersedia -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-blue-600"></i>Guru BK Spesialis Belajar
                    </h3>
                    
                    <div class="space-y-4">
                        @if(isset($gurus) && $gurus->count() > 0)
                            @foreach($gurus as $g)
                                <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg">
                                    <div class="bg-blue-100 p-3 rounded-full">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
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
                            <i class="fas fa-info-circle mr-2"></i>Info Konsultasi
                        </h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• Konsultasi dilakukan via tatap muka</li>
                            <li>• Durasi: 30-60 menit per sesi</li>
                            <li>• Gratis untuk semua siswa</li>
                            <li>• Jadwal fleksibel sesuai kesepakatan</li>
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

        function ajukanKonsultasiBelajar() {
            alert('Konsultasi belajar berhasil diajukan!\n\nGuru BK akan menghubungi Anda untuk menjadwalkan sesi konsultasi.');
            document.getElementById('formKonsultasiBelajar').reset();
        }
    </script>
</body>
</html>