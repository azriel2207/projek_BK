<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator BK - Sistem BK</title>
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
            <a href="#" class="block py-3 px-6 bg-blue-700 border-l-4 border-yellow-400">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-users mr-3"></i>Data Siswa
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-chart-bar mr-3"></i>Laporan
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-user-tie mr-3"></i>Guru BK
            </a>
           
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-blue-700">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Dashboard Koordinator BK</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Selamat datang, {{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Total Siswa</p>
                            <p class="text-2xl font-bold text-gray-800">1,248</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Konseling Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-800">86</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-comments text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Guru BK</p>
                            <p class="text-2xl font-bold text-gray-800">12</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Kasus Prioritas</p>
                            <p class="text-2xl font-bold text-gray-800">8</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Konseling selesai</p>
                                <p class="text-sm text-gray-600">Siswa: Andi Pratama - Masalah Belajar</p>
                                <p class="text-xs text-gray-500">2 jam yang lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-plus text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Konseling baru</p>
                                <p class="text-sm text-gray-600">Siswa: Siti Rahayu - Bimbingan Karir</p>
                                <p class="text-xs text-gray-500">5 jam yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-file-alt text-blue-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Buat Laporan</p>
                        </a>
                        <a href="#" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-chart-line text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Statistik</p>
                        </a>
                        
                        <a href="#" class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-users text-orange-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Kelola Guru</p>
                        </a>
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