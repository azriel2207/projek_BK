@extends('layouts.app')

@section('title', 'Laporan & Statistik - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan & Statistik</h1>
        <p class="text-gray-600">Analisis data dan statistik konseling</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-download"></i>
            <span>Export Laporan</span>
        </button>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-print"></i>
            <span>Print</span>
        </button>
        <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option>Pilih Periode</option>
            <option>Minggu Ini</option>
            <option>Bulan Ini</option>
            <option>Tahun Ini</option>
            <option>Custom</option>
        </select>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                </div>
                <span class="text-sm text-gray-500">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_konseling'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">Total Konseling</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <span class="text-sm text-gray-500">Selesai</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['konseling_selesai'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">Konseling Selesai</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <span class="text-sm text-gray-500">Pending</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['konseling_pending'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">Konseling Pending</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                </div>
                <span class="text-sm text-gray-500">Bulan Ini</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['konseling_bulan_ini'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">Konseling Bulan Ini</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Data Per Jenis Bimbingan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konseling Per Jenis Bimbingan</h3>
            <div class="space-y-4">
                @foreach($dataPerJenis as $jenis)
                @php
                    $colors = [
                        'pribadi' => 'blue',
                        'belajar' => 'green',
                        'karir' => 'purple',
                        'sosial' => 'orange'
                    ];
                    $color = $colors[$jenis->jenis_bimbingan] ?? 'gray';
                    $percentage = ($jenis->total / max($stats['total_konseling'], 1)) * 100;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium capitalize">{{ $jenis->jenis_bimbingan }}</span>
                        <span class="text-sm font-bold">{{ $jenis->total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-{{ $color }}-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Statistik</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Tingkat Penyelesaian</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $stats['total_konseling'] > 0 ? number_format(($stats['konseling_selesai'] / $stats['total_konseling']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <i class="fas fa-chart-line text-blue-600 text-3xl"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata Per Hari</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ number_format($stats['konseling_bulan_ini'] / max(date('d'), 1), 1) }}
                        </p>
                    </div>
                    <i class="fas fa-calendar-day text-green-600 text-3xl"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-purple-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa Terlayani</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ DB::table('janji_konselings')->distinct('user_id')->count('user_id') }}
                        </p>
                    </div>
                    <i class="fas fa-users text-purple-600 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Konseling Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $recentData = DB::table('janji_konselings')
                            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
                            ->select('janji_konselings.*', 'users.name')
                            ->orderBy('janji_konselings.tanggal', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp
                    @foreach($recentData as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{ $data->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="capitalize">{{ $data->jenis_bimbingan }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'menunggu' => 'yellow',
                                    'dikonfirmasi' => 'blue',
                                    'selesai' => 'green',
                                    'dibatalkan' => 'red'
                                ];
                                $color = $statusColors[$data->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ ucfirst($data->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection