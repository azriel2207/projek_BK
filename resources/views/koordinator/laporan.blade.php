<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Sistem BK</title>
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
            <a href="{{ route('koordinator.guru.index') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-user-tie mr-3"></i>Kelola Guru BK
            </a>
            <a href="{{ route('koordinator.siswa.index') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
                <i class="fas fa-users mr-3"></i>Data Siswa
            </a>
            <a href="{{ route('koordinator.laporan') }}" class="block py-3 px-6 bg-blue-700 border-l-4 border-yellow-400">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Laporan & Statistik</h2>
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
                    <h1 class="text-2xl font-bold text-gray-800">Laporan Sistem BK</h1>
                    <p class="text-gray-600">Analisis dan statistik bimbingan konseling</p>
                </div>
                <div class="flex space-x-3">
                    <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Bulan Ini</option>
                        <option>3 Bulan Terakhir</option>
                        <option>6 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                        <i class="fas fa-download"></i>
                        <span>Export PDF</span>
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total Konseling</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('janji_konselings')->count() }}</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-comments text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Rata-rata Waktu</p>
                            <p class="text-2xl font-bold text-gray-800">45m</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 5m lebih cepat
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Tingkat Kepuasan</p>
                            <p class="text-2xl font-bold text-gray-800">89%</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 3% meningkat
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Kasus Selesai</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('janji_konselings')->where('status', 'selesai')->count() }}</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 8% meningkat
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Jenis Konseling Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Jenis Konseling</h3>
                    <div class="space-y-4">
                        @php
                            $jenisKonseling = DB::table('janji_konselings')
                                ->select('jenis_bimbingan', DB::raw('count(*) as total'))
                                ->groupBy('jenis_bimbingan')
                                ->get();
                            $totalAll = $jenisKonseling->sum('total');
                        @endphp
                        
                        @foreach($jenisKonseling as $jenis)
                        @php
                            $percentage = $totalAll > 0 ? ($jenis->total / $totalAll) * 100 : 0;
                            $colors = [
                                'pribadi' => ['bg' => 'blue', 'label' => 'Pribadi'],
                                'belajar' => ['bg' => 'green', 'label' => 'Belajar'],
                                'karir' => ['bg' => 'purple', 'label' => 'Karir'],
                                'sosial' => ['bg' => 'orange', 'label' => 'Sosial']
                            ];
                            $color = $colors[$jenis->jenis_bimbingan] ?? ['bg' => 'gray', 'label' => ucfirst($jenis->jenis_bimbingan)];
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">{{ $color['label'] }}</span>
                                <span class="text-sm font-bold">{{ $jenis->total }} ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $color['bg'] }}-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status Konseling Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Konseling</h3>
                    <div class="space-y-4">
                        @php
                            $statusKonseling = DB::table('janji_konselings')
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('status')
                                ->get();
                            $totalStatus = $statusKonseling->sum('total');
                        @endphp
                        
                        @foreach($statusKonseling as $status)
                        @php
                            $percentage = $totalStatus > 0 ? ($status->total / $totalStatus) * 100 : 0;
                            $statusColors = [
                                'menunggu' => ['bg' => 'yellow', 'label' => 'Menunggu'],
                                'dikonfirmasi' => ['bg' => 'blue', 'label' => 'Dikonfirmasi'],
                                'selesai' => ['bg' => 'green', 'label' => 'Selesai'],
                                'ditolak' => ['bg' => 'red', 'label' => 'Ditolak']
                            ];
                            $statusColor = $statusColors[$status->status] ?? ['bg' => 'gray', 'label' => ucfirst($status->status)];
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">{{ $statusColor['label'] }}</span>
                                <span class="text-sm font-bold">{{ $status->total }} ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $statusColor['bg'] }}-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Report Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Generate Laporan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition border-2 border-dashed border-blue-200">
                        <i class="fas fa-file-pdf text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-blue-800">Laporan Bulanan</p>
                        <p class="text-xs text-blue-600 mt-1">PDF Report</p>
                    </button>
                    
                    <button class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition border-2 border-dashed border-green-200">
                        <i class="fas fa-chart-line text-green-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-green-800">Statistik Trend</p>
                        <p class="text-xs text-green-600 mt-1">Analytics</p>
                    </button>
                    
                    <button class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition border-2 border-dashed border-purple-200">
                        <i class="fas fa-user-tie text-purple-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-purple-800">Performa Guru</p>
                        <p class="text-xs text-purple-600 mt-1">Performance</p>
                    </button>
                    
                    <button class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg text-center transition border-2 border-dashed border-orange-200">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-orange-800">Kasus Prioritas</p>
                        <p class="text-xs text-orange-600 mt-1">Priority</p>
                    </button>
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