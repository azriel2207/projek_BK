<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru BK - Sistem BK</title>
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
<div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-blue-700 text-white">
    <div class="p-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-hands-helping text-2xl"></i>
            <h1 class="text-xl font-bold">Sistem BK</h1>
        </div>
    </div>
    
    <nav class="mt-8">
        <a href="{{ route('guru.dashboard') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
        </a>
        <a href="{{ route('guru.jadwal') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-calendar-alt mr-3"></i>Kelola Jadwal
        </a>
        <a href="{{ route('guru.siswa') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-user-friends mr-3"></i>Daftar Siswa
        </a>
        <a href="{{ route('guru.guru') }}" class="block py-3 px-6 bg-blue-600 border-l-4 border-yellow-400">
            <i class="fas fa-user-tie mr-3"></i>Daftar Guru
        </a>
        <a href="{{ route('guru.riwayat.index') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-file-medical mr-3"></i>Riwayat Konseling
        </a>
        <a href="{{ route('guru.laporan') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-chart-line mr-3"></i>Laporan & Statistik
        </a>
        <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-user-cog mr-3"></i>Profile Settings
        </a>
    </nav>
    
    <div class="absolute bottom-0 w-full p-4 border-t border-blue-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center space-x-3 text-red-300 hover:text-red-100 transition w-full">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Daftar Guru BK</h2>
                </div>
                <div class="flex items-center space-x-4">
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
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Daftar Guru BK 👨‍🏫</h1>
                        <p class="text-blue-100">Kelola dan lihat data semua guru BK yang terdaftar di sistem</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Guru BK</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $daftarGuru->total() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Guru Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $daftarGuru->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Halaman</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $daftarGuru->currentPage() }} / {{ $daftarGuru->lastPage() }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-book text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Guru</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($daftarGuru as $guru)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800">{{ ($daftarGuru->currentPage() - 1) * $daftarGuru->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-tie text-blue-600"></i>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $guru->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $guru->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $guru->role === 'guru_bk' ? 'Guru BK' : 'Guru' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ \Carbon\Carbon::parse($guru->created_at)->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p>Belum ada guru yang terdaftar</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($daftarGuru->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $daftarGuru->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) sidebar.classList.toggle('active');
            });
        }
    </script>
</body>
</html>
