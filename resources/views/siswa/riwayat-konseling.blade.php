<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Konseling - Sistem BK</title>
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
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-purple-800 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 bg-purple-700 border-l-4 border-yellow-400">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Riwayat Konseling</h2>
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
            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">Riwayat Sesi Konseling</h2>
                    <div class="flex flex-wrap gap-3">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option>Semua Status</option>
                            <option>Selesai</option>
                            <option>Dibatalkan</option>
                        </select>
                        <input type="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Riwayat List -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                @if(isset($riwayat) && count($riwayat) > 0)
                <div class="space-y-4">
                    @foreach($riwayat as $item)
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex-1 mb-4 md:mb-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-{{ $item->status == 'selesai' ? 'green' : 'red' }}-100 text-{{ $item->status == 'selesai' ? 'green' : 'red' }}-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($item->status) }}
                                </span>
                                <span class="text-sm font-medium text-gray-800">{{ $item->jenis_bimbingan_text ?? 'Bimbingan Pribadi' }}</span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ $item->keluhan ?? 'Deskripsi konseling' }}</p>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') : 'Tanggal tidak tersedia' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $item->waktu ?? 'Waktu tidak tersedia' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $item->guru_bk ?? 'Guru BK' }}
                                </span>
                            </div>
                            @if($item->catatan_konselor)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-800 mb-1">Catatan Konselor:</p>
                                <p class="text-sm text-gray-700">{{ $item->catatan_konselor }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="showDetail({{ $item->id }})" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition flex items-center">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </button>
                            @if($item->status == 'selesai')
                            <button onclick="downloadSertifikat({{ $item->id }})" class="bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm hover:bg-green-200 transition flex items-center">
                                <i class="fas fa-download mr-2"></i>Sertifikat
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-history text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Riwayat Konseling</h3>
                    <p class="text-gray-500 mb-6">Anda belum memiliki riwayat sesi konseling.</p>
                    <a href="{{ route('siswa.janji-konseling') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                        <i class="fas fa-calendar-plus mr-2"></i>Buat Janji Konseling
                    </a>
                </div>
                @endif

                <!-- Pagination -->
                @if(isset($riwayat) && count($riwayat) > 0)
                <div class="mt-6 flex justify-between items-center">
                    <p class="text-gray-600 text-sm">Menampilkan {{ count($riwayat) }} dari {{ count($riwayat) }} riwayat</p>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">Sebelumnya</button>
                        <button class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">1</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">Selanjutnya</button>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        function showDetail(id) {
            alert('Detail riwayat konseling ID: ' + id + '\n\nFitur detail sedang dikembangkan.');
        }

        function downloadSertifikat(id) {
            alert('Download sertifikat untuk ID: ' + id + '\n\nFitur download sedang dikembangkan.');
        }
    </script>
</body>
</html>