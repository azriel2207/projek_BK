<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Konseling - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('siswa.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Riwayat Konseling</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari riwayat..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <span class="text-gray-600">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Semua Jenis</option>
                    <option>Bimbingan Pribadi</option>
                    <option>Bimbingan Belajar</option>
                    <option>Bimbingan Karir</option>
                    <option>Bimbingan Sosial</option>
                </select>
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Semua Status</option>
                    <option>Selesai</option>
                    <option>Dibatalkan</option>
                </select>
                <input type="month" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">12</p>
                <p class="text-sm text-gray-600">Total Konseling</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">8</p>
                <p class="text-sm text-gray-600">Selesai</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="bg-yellow-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">2</p>
                <p class="text-sm text-gray-600">Berjalan</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">2</p>
                <p class="text-sm text-gray-600">Dibatalkan</p>
            </div>
        </div>

        <!-- Daftar Riwayat -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Riwayat 1 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">15 Okt 2024</div>
                                <div class="text-sm text-gray-500">10:00-11:00</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Karir</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bpk. Ahmad, M.Pd</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">Konsultasi pemilihan jurusan kuliah</div>
                                <div class="text-sm text-gray-500">Membahas prospek karir dan minat bakat</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Riwayat 2 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">10 Okt 2024</div>
                                <div class="text-sm text-gray-500">09:00-10:00</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Pribadi</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bpk. Budi Santoso, M.Psi</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">Mengatasi masalah kepercayaan diri</div>
                                <div class="text-sm text-gray-500">Teknik meningkatkan rasa percaya diri</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Riwayat 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">5 Okt 2024</div>
                                <div class="text-sm text-gray-500">14:00-15:00</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sosial</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ibu Siti Rahayu, S.Pd</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">Menjalin hubungan dengan teman</div>
                                <div class="text-sm text-gray-500">Strategi komunikasi yang efektif</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>