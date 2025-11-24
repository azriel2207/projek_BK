<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator BK - Sistem BK</title>
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
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-blue-800 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('koordinator.dashboard') }}" class="block py-3 px-6 bg-blue-700 border-l-4 border-yellow-400">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('koordinator.guru.index') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-user-tie mr-3"></i>Kelola Guru BK
            </a>
            <a href="{{ route('koordinator.siswa.index') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-users mr-3"></i>Data Siswa
            </a>
            <a href="{{ route('koordinator.laporan') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-chart-bar mr-3"></i>Laporan
            </a>
            <a href="{{ route('koordinator.pengaturan') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-cog mr-3"></i>Pengaturan
            </a>
            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-user-cog mr-3"></i>Profile
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-blue-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-3 text-red-300 hover:text-red-100 transition w-full">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Dashboard Koordinator BK</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-blue-100">Kelola dan monitor seluruh aktivitas bimbingan konseling sekolah</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Siswa -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['total_siswa'] }}
                            </p>
                            <p class="text-blue-600 text-sm mt-2">
                                <i class="fas fa-users"></i> Terdaftar
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Guru BK -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Guru BK</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['total_guru'] }}
                            </p>
                            <p class="text-green-600 text-sm mt-2">
                                <i class="fas fa-user-tie"></i> Aktif
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Konseling Bulan Ini -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Konseling Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['konseling_bulan_ini'] }}
                            </p>
                            <p class="text-purple-600 text-sm mt-2">
                                <i class="fas fa-comments"></i> Sesi
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Kasus Prioritas -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Menunggu Konfirmasi</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">
                                {{ $stats['menunggu_konfirmasi'] }}
                            </p>
                            <p class="text-orange-600 text-sm mt-2">
                                <i class="fas fa-exclamation-triangle"></i> Perlu tindakan
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Statistik Konseling -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Konseling Per Jenis</h3>
                    <div class="space-y-4">
                        @foreach($jenisKonselingData as $jenis)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">{{ $jenis['color']['label'] }}</span>
                                <span class="text-sm font-bold">{{ $jenis['total'] }} ({{ number_format($jenis['percentage'], 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $jenis['color']['bg'] }}-600 h-3 rounded-full transition-all duration-500" style="width: {{ $jenis['percentage'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('koordinator.guru.index') }}" class="block bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Tambah Guru BK</p>
                        </a>
                        <a href="{{ route('koordinator.laporan') }}" class="block bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-file-alt text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Buat Laporan</p>
                        </a>
                        <a href="{{ route('koordinator.pengaturan') }}" class="block bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-cog text-purple-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Pengaturan</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="{{ route('koordinator.laporan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="bg-{{ $activity->status == 'selesai' ? 'green' : ($activity->status == 'menunggu' ? 'yellow' : 'blue') }}-100 p-2 rounded-full">
                            <i class="fas fa-{{ $activity->status == 'selesai' ? 'check' : ($activity->status == 'menunggu' ? 'clock' : 'calendar') }} text-{{ $activity->status == 'selesai' ? 'green' : ($activity->status == 'menunggu' ? 'yellow' : 'blue') }}-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $activity->name }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst($activity->jenis_bimbingan) }} - {{ ucfirst($activity->status) }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
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