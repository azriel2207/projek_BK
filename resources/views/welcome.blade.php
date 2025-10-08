<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counseling System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-heart text-blue-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">Counseling System</span>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-6">
                Sistem Konseling <span class="text-blue-600">Sekolah</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Platform manajemen konseling terintegrasi untuk sekolah. 
                Kelola sesi konseling, perkembangan siswa, dan pelaporan dengan mudah.
            </p>
            
            <div class="flex justify-center space-x-4 mb-12">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                </a>
                <a href="#features" class="border border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold">
                    <i class="fas fa-info-circle mr-2"></i>Pelajari Fitur
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto mb-16">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <i class="fas fa-users text-blue-600 text-3xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Manajemen Siswa</h3>
                    <p class="text-gray-600 mt-2">Kelola data siswa dan perkembangan akademik</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <i class="fas fa-calendar-check text-green-600 text-3xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Sesi Konseling</h3>
                    <p class="text-gray-600 mt-2">Jadwalkan dan kelola sesi konseling</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <i class="fas fa-chart-line text-purple-600 text-3xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Laporan & Analisis</h3>
                    <p class="text-gray-600 mt-2">Pantau perkembangan dan buat laporan</p>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Fitur Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-user-tie text-blue-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Manajemen Konselor</h3>
                    <p class="text-gray-600">Kelola data konselor dan jadwal availability</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-sticky-note text-green-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Catatan Konseling</h3>
                    <p class="text-gray-600">Rekam dan kelola catatan sesi konseling</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Pelanggaran Siswa</h3>
                    <p class="text-gray-600">Monitor dan tindak lanjuti pelanggaran</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-chart-bar text-purple-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Laporan Development</h3>
                    <p class="text-gray-600">Pantau perkembangan siswa secara berkala</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-comments text-indigo-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Sistem Pesan</h3>
                    <p class="text-gray-600">Komunikasi internal antara siswa dan konselor</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-download text-red-500 text-2xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Export Laporan</h3>
                    <p class="text-gray-600">Generate dan export laporan dalam berbagai format</p>
                </div>
            </div>
        </div>

        <!-- Login Info -->
        <div class="bg-blue-600 text-white rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="mb-6">Gunakan kredensial berikut untuk testing:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                <div class="bg-blue-700 p-4 rounded">
                    <i class="fas fa-user-shield text-xl mb-2"></i>
                    <p class="font-semibold">Admin</p>
                    <p class="text-sm">admin@school.com</p>
                    <p class="text-sm">password123</p>
                </div>
                <div class="bg-blue-700 p-4 rounded">
                    <i class="fas fa-user-tie text-xl mb-2"></i>
                    <p class="font-semibold">Konselor</p>
                    <p class="text-sm">konselor@school.com</p>
                    <p class="text-sm">password123</p>
                </div>
                <div class="bg-blue-700 p-4 rounded">
                    <i class="fas fa-user-graduate text-xl mb-2"></i>
                    <p class="font-semibold">Siswa</p>
                    <p class="text-sm">siswa@school.com</p>
                    <p class="text-sm">password123</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Counseling System. All rights reserved.</p>
            <p class="text-gray-400 mt-2">Sistem Manajemen Konseling Sekolah Terintegrasi</p>
        </div>
    </footer>
</body>
</html>