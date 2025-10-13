<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janji Konseling - Sistem BK</title>
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
            <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 bg-purple-700 border-l-4 border-yellow-400">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-purple-700 transition">
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Janji Konseling</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Nama Siswa</span>
                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
                </div>
            @endif

            <!-- Buat Janji Baru -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Buat Janji Konseling Baru</h2>
                    <button id="toggleForm" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-plus mr-2"></i>Janji Baru
                    </button>
                </div>

                <!-- Form Janji Konseling (Awalnya tersembunyi) -->
                <div id="formJanji" class="hidden bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <form method="POST" action="{{ route('siswa.janji-konseling.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Jenis Bimbingan
                                </label>
                                <select name="jenis_bimbingan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                                    <option value="">Pilih Jenis Bimbingan</option>
                                    <option value="pribadi">Bimbingan Pribadi</option>
                                    <option value="belajar">Bimbingan Belajar</option>
                                    <option value="karir">Bimbingan Karir</option>
                                    <option value="sosial">Bimbingan Sosial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tie mr-2 text-purple-600"></i>Guru BK
                                </label>
                                <select name="guru_bk" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                                    <option value="">Pilih Guru BK</option>
                                    <option value="Bpk. Ahmad, M.Pd - Spesialis Karir">Bpk. Ahmad, M.Pd - Spesialis Karir</option>
                                    <option value="Ibu Siti Rahayu, S.Pd - Spesialis Akademik">Ibu Siti Rahayu, S.Pd - Spesialis Akademik</option>
                                    <option value="Bpk. Budi Santoso, M.Psi - Spesialis Pribadi">Bpk. Budi Santoso, M.Psi - Spesialis Pribadi</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-day mr-2 text-purple-600"></i>Tanggal Konseling
                                </label>
                                <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-purple-600"></i>Waktu
                                </label>
                                <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                                    <option value="">Pilih Waktu</option>
                                    <option value="08:00 - 09:00">08:00 - 09:00</option>
                                    <option value="09:00 - 10:00">09:00 - 10:00</option>
                                    <option value="10:00 - 11:00">10:00 - 11:00</option>
                                    <option value="13:00 - 14:00">13:00 - 14:00</option>
                                    <option value="14:00 - 15:00">14:00 - 15:00</option>
                                    <option value="15:00 - 16:00">15:00 - 16:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-dots mr-2 text-purple-600"></i>Keluhan / Permasalahan
                            </label>
                            <textarea name="keluhan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Jelaskan permasalahan yang ingin dikonsultasikan..." required></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" id="batalForm" class="px-6 py-2 text-gray-600 hover:text-gray-800 transition font-medium">Batal</button>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition font-medium flex items-center">
                                <i class="fas fa-calendar-check mr-2"></i>Buat Janji
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Janji Mendatang -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Janji Konseling Mendatang</h2>
                
                @if(isset($janjiMendatang) && count($janjiMendatang) > 0)
                <div class="space-y-4">
                    @foreach($janjiMendatang as $janji)
                    <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Bimbingan Pribadi
                                </span>
                                <span class="text-sm text-gray-600">Guru BK</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                    Menunggu
                                </span>
                            </div>
                            <p class="text-gray-700 mb-2">Deskripsi keluhan</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Tanggal Konseling
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    Waktu Konseling
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="ubahJanji(1)" class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-lg text-sm hover:bg-yellow-200 transition flex items-center">
                                <i class="fas fa-edit mr-1"></i>Ubah
                            </button>
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm hover:bg-red-200 transition flex items-center" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji ini?')">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">Belum ada janji konseling mendatang</p>
                </div>
                @endif
            </div>

            <!-- Riwayat Janji -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Janji Konseling</h2>
                
                @if(isset($riwayatJanji) && count($riwayatJanji) > 0)
                <div class="space-y-3">
                    @foreach($riwayatJanji as $riwayat)
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                    Selesai
                                </span>
                                <span class="text-sm font-medium text-gray-800">Bimbingan Pribadi</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Deskripsi konseling</p>
                            <p class="text-xs text-gray-500 flex items-center space-x-2">
                                <span>Tanggal</span>
                                <span>•</span>
                                <span>Waktu</span>
                                <span>•</span>
                                <span>Guru BK</span>
                            </p>
                        </div>
                        <button onclick="showDetail(1)" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-history text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">Belum ada riwayat konseling</p>
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Toggle form janji baru
        document.getElementById('toggleForm').addEventListener('click', function() {
            const form = document.getElementById('formJanji');
            form.classList.toggle('hidden');
        });

        document.getElementById('batalForm').addEventListener('click', function() {
            document.getElementById('formJanji').classList.add('hidden');
        });

        // Fungsi ubah janji
        function ubahJanji(id) {
            alert('Fitur ubah janji untuk ID: ' + id);
        }

        // Fungsi show detail
        function showDetail(id) {
            alert('Detail konseling untuk ID: ' + id);
        }

        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>