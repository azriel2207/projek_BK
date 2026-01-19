@extends('layouts.koordinator-layout')

@section('page-content')
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
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-star text-blue-600 text-xl"></i>
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
                        
                        @if($totalAll > 0)
                            @foreach($jenisKonseling as $jenis)
                            @php
                                $percentage = $totalAll > 0 ? ($jenis->total / $totalAll) * 100 : 0;
                                // Normalize jenis_bimbingan values
                                $jenisLower = strtolower($jenis->jenis_bimbingan);
                                $colors = [
                                    'pribadi' => ['bg' => 'blue', 'label' => 'Bimbingan Pribadi'],
                                    'personal' => ['bg' => 'blue', 'label' => 'Bimbingan Pribadi'],
                                    'belajar' => ['bg' => 'green', 'label' => 'Bimbingan Belajar'],
                                    'akademik' => ['bg' => 'green', 'label' => 'Bimbingan Akademik'],
                                    'karir' => ['bg' => 'blue', 'label' => 'Bimbingan Karir'],
                                    'sosial' => ['bg' => 'orange', 'label' => 'Bimbingan Sosial']
                                ];
                                $color = $colors[$jenisLower] ?? ['bg' => 'gray', 'label' => ucfirst($jenis->jenis_bimbingan)];
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
                        @else
                            <p class="text-gray-500 text-center py-8">Tidak ada data jenis bimbingan</p>
                        @endif
                    </div>
                </div>

                <!-- Status Konseling Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Konseling</h3>
                    <div class="space-y-4" data-status-konseling>
                        @php
                            $totalStatus = $statusKonseling->sum('total');
                        @endphp
                        
                        @if($totalStatus > 0)
                            @foreach($statusKonseling as $status)
                            @php
                                $percentage = $totalStatus > 0 ? ($status->total / $totalStatus) * 100 : 0;
                                $statusLower = strtolower($status->status);
                                $statusColors = [
                                    'menunggu' => ['bg' => 'yellow', 'label' => 'Menunggu'],
                                    'dikonfirmasi' => ['bg' => 'blue', 'label' => 'Dikonfirmasi'],
                                    'selesai' => ['bg' => 'green', 'label' => 'Selesai'],
                                    'ditolak' => ['bg' => 'red', 'label' => 'Ditolak'],
                                    'dibatalkan' => ['bg' => 'red', 'label' => 'Dibatalkan']
                                ];
                                $statusColor = $statusColors[$statusLower] ?? ['bg' => 'gray', 'label' => ucfirst($status->status)];
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
                        @else
                            <p class="text-gray-500 text-center py-8">Tidak ada data status bimbingan</p>
                        @endif
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

<script>
    // Handle periode changes
    document.getElementById('periode-select').addEventListener('change', function() {
        const periode = this.value;
        window.location.href = `{{ route('koordinator.laporan') }}?periode=${periode}`;
    });

    // Generate Laporan Bulanan PDF
    function generateLaporanBulanan() {
        try {
            // Get current periode from select or default
            const periodeSelect = document.getElementById('periode-select');
            const periode = periodeSelect ? periodeSelect.value : 'bulan_ini';
            
            // Show loading message
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            button.disabled = true;

            // Make POST request to export PDF
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("koordinator.laporan.export") }}';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken.getAttribute('content');
                form.appendChild(tokenInput);
            }

            // Add periode input
            const periodeInput = document.createElement('input');
            periodeInput.type = 'hidden';
            periodeInput.name = 'periode';
            periodeInput.value = periode;
            form.appendChild(periodeInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            // Reset button after a delay
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);

        } catch (error) {
            console.error('Error generating laporan:', error);
            alert('Terjadi kesalahan saat membuat laporan PDF. Silakan coba lagi.');
            const button = event.target.closest('button');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-file-pdf text-blue-600 text-2xl mb-2"></i><p class="text-sm font-medium text-blue-800">Laporan Bulanan</p><p class="text-xs text-blue-600 mt-1">PDF Report</p>';
            }
        }
    }
</script>
@endsection