<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Sistem BK</title>
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
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-blue-800 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('koordinator.dashboard') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('koordinator.guru') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-user-tie mr-3"></i>Kelola Guru BK
            </a>
            <a href="{{ route('koordinator.siswa') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-users mr-3"></i>Data Siswa
            </a>
            <a href="{{ route('koordinator.laporan') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-chart-bar mr-3"></i>Laporan
            </a>
            <a href="{{ route('koordinator.pengaturan') }}" class="block py-3 px-6 bg-blue-700 border-l-4 border-yellow-400">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Pengaturan Sistem</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem BK</h1>
                <p class="text-gray-600">Kelola konfigurasi dan preferensi sistem</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Side - Menu -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Menu Pengaturan</h3>
                        </div>
                        <nav class="p-2">
                            <a href="#" class="flex items-center space-x-3 p-3 text-blue-600 bg-blue-50 rounded-lg mb-2">
                                <i class="fas fa-cog w-5"></i>
                                <span>Umum</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 p-3 text-gray-600 hover:bg-gray-50 rounded-lg mb-2">
                                <i class="fas fa-bell w-5"></i>
                                <span>Notifikasi</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 p-3 text-gray-600 hover:bg-gray-50 rounded-lg mb-2">
                                <i class="fas fa-shield-alt w-5"></i>
                                <span>Keamanan</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 p-3 text-gray-600 hover:bg-gray-50 rounded-lg mb-2">
                                <i class="fas fa-users-cog w-5"></i>
                                <span>Hak Akses</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 p-3 text-gray-600 hover:bg-gray-50 rounded-lg mb-2">
                                <i class="fas fa-database w-5"></i>
                                <span>Backup Data</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Side - Content -->
                <div class="lg:col-span-2">
                    <!-- General Settings -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Umum</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah</label>
                                <input type="text" value="SMAN 1 Contoh Kota" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Sekolah</label>
                                <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="3">Jl. Contoh No. 123, Kota Contoh</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                                    <input type="text" value="2023/2024" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                    <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option>Ganjil</option>
                                        <option>Genap</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Configuration -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfigurasi Sistem</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">Notifikasi Email</p>
                                    <p class="text-sm text-gray-600">Kirim notifikasi via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">Auto Backup</p>
                                    <p class="text-sm text-gray-600">Backup otomatis setiap minggu</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">Maintenance Mode</p>
                                    <p class="text-sm text-gray-600">Nonaktifkan akses sementara</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-end space-x-3">
                            <button class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Reset
                            </button>
                            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Simpan Perubahan
                            </button>
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

        // Toggle switches
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('label');
                if (this.checked) {
                    label.querySelector('div').classList.add('peer-checked:bg-blue-600');
                } else {
                    label.querySelector('div').classList.remove('peer-checked:bg-blue-600');
                }
            });
        });
    </script>
</body>
</html>