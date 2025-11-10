<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem BK</title>
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
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-purple-800 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 bg-purple-700 border-l-4 border-yellow-400">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-briefcase mr-3"></i>Bimbingan Karir
            </a>
            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Dashboard Siswa</h2>
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
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-purple-100">Apa yang ingin Anda lakukan hari ini?</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Janji Aktif -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Janji Aktif</p>
                            <p class="text-2xl font-bold text-gray-800">2</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <a href="{{ route('siswa.janji-konseling') }}" class="text-blue-600 text-sm hover:text-blue-800 mt-4 block">
                        Lihat Detail →
                    </a>
                </div>

                <!-- Riwayat Konseling -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Riwayat</p>
                            <p class="text-2xl font-bold text-gray-800">5</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-history text-green-600"></i>
                        </div>
                    </div>
                    <a href="{{ route('siswa.riwayat-konseling') }}" class="text-green-600 text-sm hover:text-green-800 mt-4 block">
                        Lihat Riwayat →
                    </a>
                </div>

                <!-- Bimbingan Belajar -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Materi Belajar</p>
                            <p class="text-2xl font-bold text-gray-800">8</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-book text-yellow-600"></i>
                        </div>
                    </div>
                    <a href="{{ route('siswa.bimbingan-belajar') }}" class="text-yellow-600 text-sm hover:text-yellow-800 mt-4 block">
                        Pelajari →
                    </a>
                </div>

                <!-- Bimbingan Karir -->
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Materi Karir</p>
                            <p class="text-2xl font-bold text-gray-800">6</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-lg">
                            <i class="fas fa-briefcase text-red-600"></i>
                        </div>
                    </div>
                    <a href="{{ route('siswa.bimbingan-karir') }}" class="text-red-600 text-sm hover:text-red-800 mt-4 block">
                        Explore Karir →
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Janji Mendatang -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>Janji Mendatang
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Bimbingan Pribadi</p>
                                <p class="text-sm text-gray-600">Besok, 08:00 - 09:00</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Menunggu</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Bimbingan Belajar</p>
                                <p class="text-sm text-gray-600">Jumat, 10:00 - 11:00</p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Dikonfirmasi</span>
                        </div>
                    </div>
                    <a href="{{ route('siswa.janji-konseling') }}" class="block text-center mt-4 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Buat Janji Baru
                    </a>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bell mr-2 text-purple-600"></i>Aktivitas Terbaru
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                            <div class="bg-purple-100 p-2 rounded">
                                <i class="fas fa-check text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Konseling Selesai</p>
                                <p class="text-sm text-gray-600">Bimbingan Karir - 2 hari lalu</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg">
                            <div class="bg-yellow-100 p-2 rounded">
                                <i class="fas fa-book text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Materi Baru</p>
                                <p class="text-sm text-gray-600">Teknik Belajar Efektif</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <div class="bg-green-100 p-2 rounded">
                                <i class="fas fa-user-tie text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Janji Dikonfirmasi</p>
                                <p class="text-sm text-gray-600">Oleh Bu Siti Rahayu</p>
                            </div>
                        </div>
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
    </script>
</body>
</html>