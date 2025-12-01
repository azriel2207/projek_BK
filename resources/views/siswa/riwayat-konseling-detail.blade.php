<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Konseling - Sistem BK</title>
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
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 bg-purple-600 border-l-4 border-yellow-400">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Detail Riwayat Konseling</h2>
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
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('siswa.riwayat-konseling') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Informasi Konseling
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Jenis Bimbingan</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($detail->jenis_bimbingan == 'belajar') bg-blue-100 text-blue-800
                                        @elseif($detail->jenis_bimbingan == 'karir') bg-green-100 text-green-800
                                        @elseif($detail->jenis_bimbingan == 'pribadi') bg-purple-100 text-purple-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($detail->jenis_bimbingan ?? 'Umum') }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($detail->status == 'selesai') bg-green-100 text-green-800
                                        @elseif($detail->status == 'dikonfirmasi') bg-blue-100 text-blue-800
                                        @elseif($detail->status == 'menunggu') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($detail->status) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Konseling</label>
                                <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Waktu</label>
                                <p class="text-gray-900 mt-1">{{ $detail->waktu ?? 'Tidak ada' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Lokasi</label>
                                <p class="text-gray-900 mt-1">{{ $detail->lokasi ?? 'Tidak ada' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Guru BK</label>
                                <p class="text-gray-900 mt-1">{{ $detail->guru_bk ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-green-500"></i>
                            Keluhan / Permasalahan
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <p class="text-gray-700 whitespace-pre-wrap">
                                {{ $detail->keluhan ?? 'Tidak ada deskripsi keluhan' }}
                            </p>
                        </div>

                        @if($detail->catatan_konselor)
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-notebook text-purple-500"></i>
                            Catatan Konselor
                        </h3>

                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <p class="text-gray-700 whitespace-pre-wrap">
                                {{ $detail->catatan_konselor }}
                            </p>
                        </div>
                        @endif

                        @if($detail->keterangan ?? null)
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-yellow-500"></i>
                            Keterangan Tambahan
                        </h3>

                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-wrap">
                                {{ $detail->keterangan ?? '-' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-orange-500"></i>
                    Timeline Konseling
                </h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="font-medium text-gray-900">Janji Konseling Dibuat</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($detail->created_at)->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>

                    @if($detail->status == 'dikonfirmasi')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="font-medium text-gray-900">Janji Dikonfirmasi</p>
                            <p class="text-sm text-gray-500">Guru BK telah mengkonfirmasi janji konseling</p>
                        </div>
                    </div>
                    @elseif($detail->status == 'selesai')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="font-medium text-gray-900">Konseling Selesai</p>
                            <p class="text-sm text-gray-500">Sesi konseling telah selesai dan catatan tersimpan</p>
                        </div>
                    </div>
                    @elseif($detail->status == 'dibatalkan')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-red-500 rounded-full mt-2"></div>
                        <div>
                            <p class="font-medium text-gray-900">Janji Dibatalkan</p>
                            <p class="text-sm text-gray-500">Janji konseling telah dibatalkan</p>
                        </div>
                    </div>
                    @endif
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
