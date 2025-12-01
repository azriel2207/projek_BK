<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru BK - Sistem BK</title>
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
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); }
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal.active { display: flex; align-items: center; justify-content: center; }
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
        <a href="{{ route('guru.dashboard') }}" class="block py-3 px-6 bg-blue-600 border-l-4 border-yellow-400">
            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
        </a>
        <a href="{{ route('guru.jadwal') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-calendar-alt mr-3"></i>Kelola Jadwal
        </a>
        <a href="{{ route('guru.siswa') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
            <i class="fas fa-user-friends mr-3"></i>Daftar Siswa
        </a>
        <a href="{{ route('guru.guru') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
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
</div>    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center p-4">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Dashboard Guru BK</h2>
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
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-green-100">Ada {{ $stats['permintaan_menunggu'] ?? 0 }} permintaan konseling yang menunggu konfirmasi</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Permintaan Baru</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['permintaan_menunggu'] ?? 0 }}</p>
                            <p class="text-orange-600 text-sm mt-2">
                                <i class="fas fa-clock"></i> Menunggu konfirmasi
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-inbox text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Konseling Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['konseling_hari_ini'] ?? 0 }}</p>
                            <p class="text-blue-600 text-sm mt-2">
                                <i class="fas fa-calendar-check"></i> Sudah dikonfirmasi
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_siswa'] ?? 0 }}</p>
                            <p class="text-green-600 text-sm mt-2">
                                <i class="fas fa-users"></i> Aktif bimbingan
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-user-graduate text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Selesai Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['selesai_bulan_ini'] ?? 0 }}</p>
                            <p class="text-purple-600 text-sm mt-2">
                                <i class="fas fa-check-circle"></i> Konseling selesai
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-tasks text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Permintaan Konseling -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-inbox mr-2 text-orange-600"></i>Permintaan Konseling Baru
                        </h3>
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ isset($permintaanBaru) ? $permintaanBaru->count() : 0 }} Baru
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        @if(isset($permintaanBaru) && $permintaanBaru->count() > 0)
                            @foreach($permintaanBaru as $janji)
                            <div class="flex items-start justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-100 transition" data-id="{{ $janji->id }}">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-yellow-100 p-2 rounded-full">
                                            <i class="fas fa-user text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $janji->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $janji->jenis_bimbingan }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 ml-11">{{ Str::limit($janji->keluhan, 80) }}</p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 ml-11 mt-2">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($janji->tanggal)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $janji->waktu }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <form action="{{ route('guru.permintaan.konfirmasi', $janji->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center whitespace-nowrap">
                                            <i class="fas fa-check mr-1"></i>Konfirmasi
                                        </button>
                                    </form>
                                    <button onclick="showDetailModal({{ $janji->id }}, '{{ addslashes($janji->name) }}', '{{ addslashes($janji->jenis_bimbingan) }}', '{{ addslashes($janji->keluhan) }}', '{{ \Carbon\Carbon::parse($janji->tanggal)->translatedFormat('d M Y') }}', '{{ $janji->waktu }}')" 
                                            class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>Tidak ada permintaan baru</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('guru.permintaan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Permintaan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>Jadwal Hari Ini
                    </h3>
                    
                    <div class="space-y-3">
                        @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                            @foreach($jadwalHariIni as $jadwal)
                            <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $jadwal->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $jadwal->jenis_bimbingan }}</p>
                                    </div>
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">{{ $jadwal->waktu }}</span>
                                </div>
                                <p class="text-xs text-gray-700">{{ Str::limit($jadwal->keluhan, 50) }}</p>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check text-gray-400 text-2xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Tidak ada jadwal hari ini</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('guru.jadwal.tambah') }}" class="block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm text-center">
                            <i class="fas fa-calendar-plus mr-2"></i>Tambah Jadwal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Riwayat Konseling (Stats Only) -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Konseling Selesai Bulan Ini</p>
                        <p class="text-3xl font-bold text-purple-600">{{ isset($stats['selesai_bulan_ini']) ? $stats['selesai_bulan_ini'] : 0 }}</p>
                    </div>
                    <div class="text-5xl text-purple-200">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-purple-200">
                    <a href="{{ route('guru.riwayat.index') }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                        <i class="fas fa-arrow-right mr-1"></i>Lihat Riwayat Lengkap
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white p-6 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">
                        <i class="fas fa-info-circle mr-2"></i>Detail Permintaan Konseling
                    </h3>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-lg">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-500">Nama Siswa</label>
                        <p id="modal-nama" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-green-50 rounded-lg">
                        <label class="text-sm font-medium text-gray-500 flex items-center">
                            <i class="fas fa-bookmark mr-2 text-green-600"></i>Jenis Bimbingan
                        </label>
                        <p id="modal-jenis" class="text-gray-800 font-medium mt-1"></p>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <label class="text-sm font-medium text-gray-500 flex items-center">
                            <i class="fas fa-calendar mr-2 text-yellow-600"></i>Tanggal
                        </label>
                        <p id="modal-tanggal" class="text-gray-800 font-medium mt-1"></p>
                    </div>
                </div>

                <div class="p-4 bg-purple-50 rounded-lg">
                    <label class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="fas fa-clock mr-2 text-purple-600"></i>Waktu
                    </label>
                    <p id="modal-waktu" class="text-gray-800 font-medium mt-1"></p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <label class="text-sm font-medium text-gray-500 flex items-center mb-2">
                        <i class="fas fa-comment-dots mr-2 text-gray-600"></i>Keluhan / Permasalahan
                    </label>
                    <p id="modal-keluhan" class="text-gray-700 leading-relaxed"></p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button onclick="konfirmasiDariModal()" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>Konfirmasi Permintaan
                    </button>
                    <button onclick="closeDetailModal()" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentModalId = null;

        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) sidebar.classList.toggle('active');
            });
        }

        function konfirmasiJanji(id) {
            if(!id) return;
            if(confirm('Konfirmasi janji konseling ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/guru/permintaan/${id}/konfirmasi`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showDetailModal(id, nama, jenis, keluhan, tanggal, waktu) {
            currentModalId = id;
            document.getElementById('modal-nama').textContent = nama || '';
            document.getElementById('modal-jenis').textContent = jenis || '';
            document.getElementById('modal-keluhan').textContent = keluhan || '';
            document.getElementById('modal-tanggal').textContent = tanggal || '';
            document.getElementById('modal-waktu').textContent = waktu || '';
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() {
            const dm = document.getElementById('detailModal');
            if(dm) dm.classList.remove('active');
            currentModalId = null;
        }

        function konfirmasiDariModal() {
            if(currentModalId) {
                closeDetailModal();
                konfirmasiJanji(currentModalId);
            }
        }

        function tambahCatatan(id) {
            const modalHtml = `
                <div id="catatanModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-md w-full">
                        <div class="bg-purple-600 text-white p-6 rounded-t-xl">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold">Tambah Catatan Konseling</h3>
                                <button id="closeCatatanBtn" class="text-white hover:text-gray-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <form id="catatanForm" method="POST" action="/guru/catatan/${id}" class="p-6">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Konselor</label>
                                <textarea name="catatan_konselor" rows="5" required 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                          placeholder="Tulis catatan hasil konseling..."></textarea>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" id="catatanBatal" 
                                        class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const closeBtn = document.getElementById('closeCatatanBtn');
            const batalBtn = document.getElementById('catatanBatal');
            if(closeBtn) closeBtn.addEventListener('click', closeCatatanModal);
            if(batalBtn) batalBtn.addEventListener('click', closeCatatanModal);
        }

        function closeCatatanModal() {
            const modal = document.getElementById('catatanModal');
            if (modal) modal.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 5000);
                });
            }, 100);
        });
    </script>
</body>
</html>