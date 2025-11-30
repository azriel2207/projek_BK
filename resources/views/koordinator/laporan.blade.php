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
            </a>            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-700 transition">
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
                    <select id="periode-select" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="3_bulan" {{ $periode == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="6_bulan" {{ $periode == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                        <option value="tahun_ini" {{ $periode == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                   
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total Konseling</p>
                            <p class="text-2xl font-bold text-gray-800" data-total-konseling>{{ $statistik['total_konseling'] }}</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> {{ number_format($statistik['persentase_total'], 1) }}% dari bulan lalu
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
                            <p class="text-2xl font-bold text-gray-800">{{ $statistik['rata_rata_waktu'] }}m</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> {{ abs($statistik['perubahan_waktu']) }}m lebih cepat
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
                            <p class="text-2xl font-bold text-gray-800">{{ $statistik['tingkat_kepuasan'] }}%</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> {{ $statistik['perubahan_kepuasan'] }}% meningkat
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
                            <p class="text-2xl font-bold text-gray-800" data-kasus-selesai>{{ $statistik['kasus_selesai'] }}</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> {{ number_format($statistik['persentase_selesai'], 1) }}% meningkat
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
                    <div class="space-y-4" data-jenis-konseling>
                        @php
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
                    <div class="space-y-4" data-status-konseling>
                        @php
                            $totalStatus = $statusKonseling->sum('total');
                        @endphp
                        
                        @foreach($statusKonseling as $status)
                        @php
                            $percentage = $totalStatus > 0 ? ($status->total / $totalStatus) * 100 : 0;
                            $statusColors = [
                                'menunggu' => ['bg' => 'yellow', 'label' => 'Menunggu'],
                                'dikonfirmasi' => ['bg' => 'blue', 'label' => 'Dikonfirmasi'],
                                'selesai' => ['bg' => 'green', 'label' => 'Selesai'],
                                'ditolak' => ['bg' => 'red', 'label' => 'Ditolak'],
                                'dibatalkan' => ['bg' => 'red', 'label' => 'Dibatalkan']
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
                <h3 class="text-lg font-semibold text-gray-800 mb-4"></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button onclick="generateLaporanBulanan()" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition border-2 border-dashed border-blue-200 cursor-pointer">
                        <i class="fas fa-file-pdf text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-blue-800">Laporan Bulanan</p>
                        <p class="text-xs text-blue-600 mt-1">PDF Report</p>
                    </button>
                    
                </div>
            </div>
        </main>
    </div>

    <!-- Modal untuk Laporan Bulanan -->
    <div id="modalBulanan" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Laporan Bulanan</h3>
                <button onclick="closeModal('modalBulanan')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formBulanan" method="POST" action="{{ route('koordinator.laporan.generate-bulanan') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bulan</label>
                        <select name="bulan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun</label>
                        <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @foreach(range(now()->year - 2, now()->year) as $year)
                                <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalBulanan')" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                        <i class="fas fa-download"></i>
                        <span>Generate PDF</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk Hasil Laporan -->
    <div id="modalHasil" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-3/4 max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">Hasil Laporan</h3>
                <button onclick="closeModal('modalHasil')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 overflow-auto" id="modalContent">
                <!-- Konten akan diisi oleh JavaScript -->
            </div>
            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t">
                <button onclick="closeModal('modalHasil')" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Tutup
                </button>
                <button onclick="exportHasil()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center space-x-2">
                    <i class="fas fa-file-export"></i>
                    <span>Export Data</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Update periode
        document.getElementById('periode-select').addEventListener('change', function() {
            const periode = this.value;
            
            fetch('{{ route("koordinator.laporan.update-periode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ periode: periode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateDashboard(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function updateDashboard(data) {
            // Update summary cards
            document.querySelector('[data-total-konseling]').textContent = data.statistik.total_konseling;
            document.querySelector('[data-kasus-selesai]').textContent = data.statistik.kasus_selesai;
            
            // Update persentase
            updatePersentaseText('[data-total-konseling]', data.statistik.persentase_total, 'dari bulan lalu');
            updatePersentaseText('[data-kasus-selesai]', data.statistik.persentase_selesai, 'meningkat');
            
            // Update progress bars untuk jenis konseling
            updateProgressBars(data.jenisKonseling, 'jenis-konseling');
            
            // Update progress bars untuk status konseling  
            updateProgressBars(data.statusKonseling, 'status-konseling');
        }

        function updatePersentaseText(selector, persentase, suffix) {
            const card = document.querySelector(selector).closest('.bg-white');
            const persentaseElement = card.querySelector('.text-green-600');
            if (persentaseElement) {
                const arrow = persentase >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                const textColor = persentase >= 0 ? 'text-green-600' : 'text-red-600';
                persentaseElement.className = `${textColor} text-sm mt-1`;
                persentaseElement.innerHTML = `<i class="fas ${arrow}"></i> ${Math.abs(persentase).toFixed(1)}% ${suffix}`;
            }
        }

        function updateProgressBars(data, type) {
            const container = document.querySelector(`[data-${type}]`);
            if (container) {
                container.innerHTML = '';
                
                // Calculate total
                const total = data.reduce((sum, item) => sum + item.total, 0);
                
                data.forEach(item => {
                    const percentage = total > 0 ? (item.total / total) * 100 : 0;
                    
                    const colorClass = type === 'jenis-konseling' ? 
                        getJenisColor(item.jenis_bimbingan) : 
                        getStatusColor(item.status);
                        
                    const label = type === 'jenis-konseling' ? 
                        getJenisLabel(item.jenis_bimbingan) : 
                        getStatusLabel(item.status);
                    
                    const progressHtml = `
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">${label}</span>
                                <span class="text-sm font-bold">${item.total} (${percentage.toFixed(1)}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-500 ${colorClass}" style="width: ${percentage}%"></div>
                            </div>
                        </div>
                    `;
                    
                    container.innerHTML += progressHtml;
                });
            }
        }

        function getJenisColor(jenis) {
            const colors = {
                'pribadi': 'bg-blue-600',
                'belajar': 'bg-green-600', 
                'karir': 'bg-purple-600',
                'sosial': 'bg-orange-600'
            };
            return colors[jenis] || 'bg-gray-600';
        }

        function getStatusColor(status) {
            const colors = {
                'menunggu': 'bg-yellow-600',
                'dikonfirmasi': 'bg-blue-600',
                'selesai': 'bg-green-600',
                'ditolak': 'bg-red-600',
                'dibatalkan': 'bg-red-600'
            };
            return colors[status] || 'bg-gray-600';
        }

        function getJenisLabel(jenis) {
            const labels = {
                'pribadi': 'Bimbingan Pribadi',
                'belajar': 'Bimbingan Belajar',
                'karir': 'Bimbingan Karir', 
                'sosial': 'Bimbingan Sosial'
            };
            return labels[jenis] || jenis;
        }

        function getStatusLabel(status) {
            const labels = {
                'menunggu': 'Menunggu',
                'dikonfirmasi': 'Dikonfirmasi',
                'selesai': 'Selesai',
                'ditolak': 'Ditolak',
                'dibatalkan': 'Dibatalkan'
            };
            return labels[status] || status;
        }

        // Export PDF
        function exportPdf() {
            const periode = document.getElementById('periode-select').value;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("koordinator.laporan.export") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const periodeInput = document.createElement('input');
            periodeInput.type = 'hidden';
            periodeInput.name = 'periode';
            periodeInput.value = periode;
            
            form.appendChild(csrfToken);
            form.appendChild(periodeInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Fungsi untuk Generate Laporan
        function generateLaporanBulanan() {
            document.getElementById('modalBulanan').classList.remove('hidden');
        }

       
        
            
            showModal('Performa Guru', content);


        function showKasusPrioritas(data) {
            const content = `
                <div class="space-y-4">
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-orange-800 mb-2">Kasus Prioritas</h4>
                        <p>Total ${data.total_kasus} kasus yang membutuhkan perhatian</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Siswa</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Jenis Bimbingan</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Tanggal</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Hari Menunggu</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.data.map(kasus => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-2">${kasus.siswa}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">${kasus.jenis_bimbingan}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                                kasus.status === 'selesai' ? 'bg-green-100 text-green-800' :
                                                kasus.status === 'dikonfirmasi' ? 'bg-blue-100 text-blue-800' :
                                                'bg-yellow-100 text-yellow-800'
                                            }">
                                                ${kasus.status}
                                            </span>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">${kasus.tanggal}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">${kasus.hari_menunggu} hari</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                                kasus.prioritas === 'Tinggi' ? 'bg-red-100 text-red-800' :
                                                kasus.prioritas === 'Sedang' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-green-100 text-green-800'
                                            }">
                                                ${kasus.prioritas}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            showModal('Kasus Prioritas', content);
        }

        // Fungsi helper untuk modal
        function showModal(title, content) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('modalHasil').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function showLoading(message) {
            showModal('Memuat...', `
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">${message}</p>
                    </div>
                </div>
            `);
        }

        function exportHasil() {
            alert('Fitur export data akan diimplementasi');
            // Bisa ditambahkan fungsi untuk export ke Excel/CSV
        }

        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>