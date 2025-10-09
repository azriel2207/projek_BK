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
            <a href="#" class="block py-3 px-6 bg-purple-700 border-l-4 border-yellow-400">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="#" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-briefcase mr-3"></i>Bimbingan Karir
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
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl shadow-sm p-6 text-white mb-8">
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="opacity-90">Sistem Bimbingan Konseling siap membantumu dalam perkembangan akademik dan pribadi</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Konseling Aktif</p>
                            <p class="text-2xl font-bold text-gray-800">2</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-comments text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold text-gray-800">5</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Janji Mendatang</p>
                            <p class="text-2xl font-bold text-gray-800">1</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-calendar text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Janji Mendatang -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Janji Konseling Mendatang</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-medium">Bimbingan Karir</p>
                                <p class="text-sm text-gray-600">Bpk. Ahmad, Guru BK</p>
                                <p class="text-xs text-gray-500">Besok, 10:00 - 11:00</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Terjadwal</span>
                        </div>
                    </div>
                    <button class="w-full mt-4 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i>Buat Janji Baru
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Layanan BK</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-user-circle text-blue-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Bimbingan Pribadi</p>
                        </a>
                        <a href="#" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-book text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Bimbingan Belajar</p>
                        </a>
                        <a href="#" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-briefcase text-purple-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Bimbingan Karir</p>
                        </a>
                        <a href="#" class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-users text-orange-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Bimbingan Sosial</p>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>