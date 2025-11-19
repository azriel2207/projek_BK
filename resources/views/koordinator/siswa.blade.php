<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Sistem BK</title>
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
            <a href="{{ route('koordinator.siswa') }}" class="block py-3 px-6 bg-blue-700 border-l-4 border-yellow-400">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Data Siswa</h2>
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
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Siswa</h1>
                    <p class="text-gray-600">Kelola data siswa dan riwayat konseling</p>
                </div>
                <div class="flex space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Cari siswa..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                        <i class="fas fa-download"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total Siswa</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('users')->where('role', 'siswa')->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Aktif Konseling</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('janji_konselings')->where('status', 'dikonfirmasi')->distinct('user_id')->count('user_id') }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-comments text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Selesai Konseling</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('janji_konselings')->where('status', 'selesai')->distinct('user_id')->count('user_id') }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Butuh Perhatian</p>
                            <p class="text-2xl font-bold text-gray-800">12</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Siswa</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Konseling</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Terakhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $siswaList = DB::table('users')->where('role', 'siswa')->get();
                            @endphp
                            
                            @foreach($siswaList as $siswa)
                            @php
                                $konselingCount = DB::table('janji_konselings')->where('user_id', $siswa->id)->count();
                                $lastKonseling = DB::table('janji_konselings')->where('user_id', $siswa->id)->orderBy('created_at', 'desc')->first();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user-graduate text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $siswa->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $siswa->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">XII IPA 1</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $konselingCount }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($lastKonseling)
                                        @if($lastKonseling->status == 'selesai')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                        @elseif($lastKonseling->status == 'dikonfirmasi')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Berjalan
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-history"></i> Riwayat
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($siswaList->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                                    <p>Belum ada data siswa</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $siswaList->count() }}</span> siswa
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm bg-white hover:bg-gray-50">
                                Sebelumnya
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm bg-white hover:bg-gray-50">
                                Selanjutnya
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
    </script>
</body>
</html>