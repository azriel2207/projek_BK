@extends('layouts.siswa-layout')

@section('page-content')
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl shadow-sm p-6 mb-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                        <p class="text-purple-100">Kelola jadwal konseling dan lihat perkembangan bimbingan Anda</p>
                    </div>
                    <div class="text-4xl">
                        <i class="fas fa-user-graduate"></i>
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
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Janji Menunggu</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['janji_menunggu'] ?? 0 }}</p>
                            <p class="text-blue-600 text-sm mt-2">
                                <i class="fas fa-clock"></i> Belum dikonfirmasi
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Janji Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['janji_hari_ini'] ?? 0 }}</p>
                            <p class="text-green-600 text-sm mt-2">
                                <i class="fas fa-calendar-day"></i> Terjadwal
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-checkmark text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Konseling</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_konseling'] ?? 0 }}</p>
                            <p class="text-purple-600 text-sm mt-2">
                                <i class="fas fa-history"></i> Selesai
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-file-alt text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['konseling_bulan_ini'] ?? 0 }}</p>
                            <p class="text-orange-600 text-sm mt-2">
                                <i class="fas fa-chart-line"></i> Konseling terbaru
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Janji Mendatang -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-blue-600"></i>Janji Konseling Mendatang
                        </h3>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ isset($janjiMendatang) ? $janjiMendatang->count() : 0 }} Janji
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        @if(isset($janjiMendatang) && $janjiMendatang->count() > 0)
                            @foreach($janjiMendatang as $janji)
                            <div class="flex items-start justify-between p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500 hover:bg-blue-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-blue-100 p-2 rounded-full">
                                            <i class="fas fa-user-tie text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ data_get($janji, 'guru_name', 'Guru BK') }}</p>
                                            <p class="text-sm text-gray-600">{{ data_get($janji, 'jenis_bimbingan', 'Bimbingan Umum') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 ml-11">{{ Str::limit(data_get($janji, 'keluhan', '-'), 80) }}</p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 ml-11 mt-2">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse(data_get($janji, 'tanggal', now()))->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ data_get($janji, 'waktu', '-') }}
                                        </span>
                                        <span class="flex items-center">
                                            <span class="bg-{{ data_get($janji, 'status') === 'dikonfirmasi' ? 'green' : 'yellow' }}-100 text-{{ data_get($janji, 'status') === 'dikonfirmasi' ? 'green' : 'yellow' }}-800 px-2 py-0.5 rounded text-xs font-medium">
                                                {{ ucfirst(data_get($janji, 'status', 'menunggu')) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <button onclick="editJanji({{ data_get($janji, 'id') }})" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm hover:bg-yellow-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-edit mr-1"></i>Ubah
                                    </button>
                                    <button onclick="batalJanji({{ data_get($janji, 'id') }})" class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm hover:bg-red-200 transition flex items-center whitespace-nowrap">
                                        <i class="fas fa-times mr-1"></i>Batal
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>Belum ada janji konseling</p>
                                <a href="{{ route('siswa.janji-konseling') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                    Buat janji baru →
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('siswa.janji-konseling') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Janji <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Statistik Bimbingan -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-purple-600"></i>Konseling Per Jenis
                    </h3>
                    
                    <div class="space-y-4">
                        @php
                            $colors = [
                                'pribadi' => '#2563eb',
                                'belajar' => '#16a34a',
                                'karir' => '#7c3aed',
                                'sosial' => '#f97316'
                            ];
                        @endphp

                        @if(isset($dataPerJenis) && count($dataPerJenis) > 0)
                            @foreach($dataPerJenis as $item)
                                @php
                                    $jenis = strtolower(data_get($item, 'jenis_bimbingan', 'pribadi'));
                                    $warna = $colors[$jenis] ?? '#6b7280';
                                    $persentase = data_get($item, 'persentase', 0);
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">{{ ucfirst($jenis) }}</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ data_get($item, 'total', 0) }} ({{ $persentase }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full" style="width: {{ $persentase }}%; background-color: {{ $warna }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-6 text-gray-500">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p class="text-sm">Belum ada data konseling</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Konseling Terbaru -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>Riwayat Konseling Terbaru
                    </h3>
                    <a href="{{ route('siswa.riwayat-konseling') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru BK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Bimbingan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if(isset($riwayatKonseling) && $riwayatKonseling->count() > 0)
                                @foreach($riwayatKonseling->take(5) as $riwayat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-800">{{ data_get($riwayat, 'guru_name', 'Guru BK') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                            {{ ucfirst(data_get($riwayat, 'jenis_bimbingan', '-')) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse(data_get($riwayat, 'tanggal', now()))->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                            {{ ucfirst(data_get($riwayat, 'status', 'selesai')) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button onclick="lihatDetail({{ data_get($riwayat, 'id') }})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada riwayat konseling
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) sidebar.classList.toggle('active');
            });
        }

        function editJanji(id) {
            // Redirect ke form edit janji
            window.location.href = `/siswa/janji-konseling/${id}/edit`;
        }

        function batalJanji(id) {
            if (confirm('Apakah Anda yakin ingin membatalkan janji ini?')) {
                // Delete via POST method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/siswa/janji-konseling/${id}`;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function lihatDetail(id) {
            window.location.href = `/siswa/riwayat-konseling/${id}`;
        }
    </script>
@endsection
