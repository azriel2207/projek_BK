<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru BK - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .main-content {
            margin-left: 16rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -16rem;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-green-800 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('guru.dashboard') }}" class="block py-3 px-6 bg-green-700 border-l-4 border-yellow-400">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-green-700 transition">
                <i class="fas fa-calendar-alt mr-3"></i>Kelola Jadwal
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-green-700 transition">
                <i class="fas fa-user-friends mr-3"></i>Daftar Siswa
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-green-700 transition">
                <i class="fas fa-file-medical mr-3"></i>Catatan Konseling
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-green-700 transition">
                <i class="fas fa-chart-line mr-3"></i>Laporan & Statistik
            </a>
            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-green-700 transition">
                <i class="fas fa-user-cog mr-3"></i>Profile Settings
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-green-700">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Dashboard Guru BK</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                        </button>
                    </div>
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-green-100">Ada {{ $stats['permintaan_menunggu'] ?? 5 }} permintaan konseling yang menunggu konfirmasi</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Permintaan -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Permintaan Baru</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['permintaan_menunggu'] ?? 5 }}
                            </p>
                            <p class="text-orange-600 text-sm mt-2">
                                <i class="fas fa-clock"></i> Menunggu konfirmasi
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-inbox text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Konseling Hari Ini -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Konseling Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['konseling_hari_ini'] ?? 3 }}
                            </p>
                            <p class="text-blue-600 text-sm mt-2">
                                <i class="fas fa-calendar-check"></i> Sudah dikonfirmasi
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Siswa Bimbingan -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['total_siswa'] ?? 48 }}
                            </p>
                            <p class="text-green-600 text-sm mt-2">
                                <i class="fas fa-users"></i> Aktif bimbingan
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-user-graduate text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Konseling Selesai -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Selesai Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['selesai_bulan_ini'] ?? 23 }}
                            </p>
                            <p class="text-purple-600 text-sm mt-2">
                                <i class="fas fa-check-circle"></i> Konseling selesai
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-tasks text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Permintaan Konseling Menunggu Konfirmasi -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-inbox mr-2 text-orange-600"></i>Permintaan Konseling Baru
                        </h3>
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $permintaanBaru->count() ?? 5 }} Baru
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        @if(isset($permintaanBaru) && $permintaanBaru->count() > 0)
                            @foreach($permintaanBaru as $janji)
                            <div class="flex items-start justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-yellow-100 p-2 rounded-full">
                                            <i class="fas fa-user text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $janji->user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $janji->jenis_bimbingan_text }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 ml-11">{{ Str::limit($janji->keluhan, 80) }}</p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 ml-11 mt-2">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($janji->tanggal)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $janji->waktu }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <form action="#" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="dikonfirmasi">
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center whitespace-nowrap">
                                            <i class="fas fa-check mr-1"></i>Konfirmasi
                                        </button>
                                    </form>
                                    <button onclick="showDetail({{ $janji->id }})" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Data Contoh jika tidak ada data real -->
                            <div class="flex items-start justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-yellow-100 p-2 rounded-full">
                                            <i class="fas fa-user text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Ahmad Fauzi</p>
                                            <p class="text-sm text-gray-600">Bimbingan Pribadi</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 ml-11">Membutuhkan konseling terkait masalah keluarga yang mempengaruhi prestasi belajar...</p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 ml-11 mt-2">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::now()->addDays(1)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            10:00 - 11:00
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <button onclick="konfirmasiJanji(1)" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-check mr-1"></i>Konfirmasi
                                    </button>
                                    <button onclick="showDetail(1)" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-start justify-between p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500 hover:bg-blue-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-blue-100 p-2 rounded-full">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Siti Nurhaliza</p>
                                            <p class="text-sm text-gray-600">Bimbingan Belajar</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 ml-11">Kesulitan memahami materi Matematika, khususnya pada materi aljabar...</p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 ml-11 mt-2">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::now()->addDays(2)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            13:00 - 14:00
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <button onclick="konfirmasiJanji(2)" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-check mr-1"></i>Konfirmasi
                                    </button>
                                    <button onclick="showDetail(2)" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Permintaan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Jadwal Konseling Hari Ini -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>Jadwal Hari Ini
                    </h3>
                    
                    <div class="space-y-3">
                        @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                            @foreach($jadwalHariIni as $jadwal)
                            <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $jadwal->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $jadwal->jenis_bimbingan_text }}</p>
                                    </div>
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">{{ $jadwal->waktu }}</span>
                                </div>
                                <p class="text-xs text-gray-700">{{ Str::limit($jadwal->keluhan, 50) }}</p>
                            </div>
                            @endforeach
                        @else
                            <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">Andi Pratama</p>
                                        <p class="text-xs text-gray-600">Bimbingan Belajar</p>
                                    </div>
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">10:00</span>
                                </div>
                                <p class="text-xs text-gray-700">Kesulitan fokus belajar</p>
                            </div>

                            <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">Dewi Lestari</p>
                                        <p class="text-xs text-gray-600">Bimbingan Karir</p>
                                    </div>
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs">14:00</span>
                                </div>
                                <p class="text-xs text-gray-700">Konsultasi pemilihan jurusan</p>
                            </div>

                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check text-gray-400 text-2xl mb-2"></i>
                                <p class="text-gray-500 text-sm">3 jadwal konseling</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-calendar-plus mr-2"></i>Tambah Jadwal
                        </button>
                    </div>
                </div>
            </div>

            <!-- Riwayat Konseling Terbaru -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>Riwayat Konseling Terbaru
                    </h3>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Bimbingan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if(isset($riwayatKonseling) && $riwayatKonseling->count() > 0)
                                @foreach($riwayatKonseling->take(5) as $riwayat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-800">{{ $riwayat->user->name }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                            {{ $riwayat->jenis_bimbingan_text }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                            {{ ucfirst($riwayat->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button onclick="tambahCatatan({{ $riwayat->id }})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i>Catatan
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-800">Ahmad Fauzi</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pribadi</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">10 Nov 2024</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button onclick="tambahCatatan(1)" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i>Catatan
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-800">Siti Nurhaliza</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Belajar</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">09 Nov 2024</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button onclick="tambahCatatan(2)" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i>Catatan
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        function konfirmasiJanji(id) {
            if(confirm('Konfirmasi janji konseling ini?')) {
                alert('Janji konseling ID: ' + id + ' dikonfirmasi!\n\nFitur ini akan terhubung dengan backend.');
                // Di production, akan ada form submit atau AJAX request
            }
        }

        function showDetail(id) {
            alert('Menampilkan detail permintaan konseling ID: ' + id + '\n\nFitur detail sedang dikembangkan.');
        }

        function tambahCatatan(id) {
            alert('Menambahkan catatan konseling untuk ID: ' + id + '\n\nFitur catatan sedang dikembangkan.');
        }

        // Auto hide success message
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-100');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>