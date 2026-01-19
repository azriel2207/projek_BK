@extends('layouts.siswa-layout')

@section('title', 'Riwayat Konseling - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">Riwayat Sesi Konseling</h2>
                    <form method="GET" action="{{ route('siswa.riwayat-konseling') }}" class="flex flex-wrap gap-3">
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="dikonfirmasi" @if(request('status') == 'dikonfirmasi') selected @endif>Dikonfirmasi</option>
                            <option value="dibatalkan" @if(request('status') == 'dibatalkan') selected @endif>Dibatalkan</option>
                        </select>
                        <input type="month" name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('bulan') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if(request('status') || request('bulan'))
                        <a href="{{ route('siswa.riwayat-konseling') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Riwayat List -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                @if(request('status') || request('bulan'))
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Filter Aktif:</strong>
                        @if(request('status'))
                            Status: <span class="font-semibold">{{ ucfirst(request('status')) }}</span>
                        @endif
                        @if(request('bulan'))
                            Bulan: <span class="font-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', request('bulan'))->translatedFormat('F Y') }}</span>
                        @endif
                    </p>
                </div>
                @endif

                @if(isset($riwayat) && count($riwayat) > 0)
                <div class="space-y-4">
                    @foreach($riwayat as $item)
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex-1 mb-4 md:mb-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="px-3 py-1 rounded-full text-sm font-medium" style="background-color: {{ $item->status == 'selesai' ? '#dcfce7' : '#fee2e2' }}; color: {{ $item->status == 'selesai' ? '#15803d' : '#991b1b' }};">
                                    {{ ucfirst($item->status) }}
                                </span>
                                <span class="text-sm font-medium text-gray-800">{{ $item->jenis_bimbingan_text ?? 'Bimbingan Pribadi' }}</span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ $item->keluhan ?? 'Deskripsi konseling' }}</p>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : 'Tanggal tidak tersedia' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $item->waktu ?? 'Waktu tidak tersedia' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $item->guru_bk ?? 'Guru BK' }}
                                </span>
                            </div>
                            @if($item->catatan_konselor)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-800 mb-1">Catatan Konselor:</p>
                                <p class="text-sm text-gray-700">{{ $item->catatan_konselor }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('siswa.riwayat-konseling-detail', $item->id) }}" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm hover:bg-blue-200 transition inline-flex items-center">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-history text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Riwayat Konseling</h3>
                    <p class="text-gray-500 mb-6">Anda belum memiliki riwayat sesi konseling.</p>
                    <a href="{{ route('siswa.janji-konseling') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-calendar-plus mr-2"></i>Buat Janji Konseling
                    </a>
                </div>
                @endif

                <!-- Pagination -->
                @if(isset($riwayat) && count($riwayat) > 0)
                <div class="mt-6 flex justify-between items-center">
                    <p class="text-gray-600 text-sm">Menampilkan {{ count($riwayat) }} dari {{ count($riwayat) }} riwayat</p>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">Sebelumnya</button>
                        <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">1</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">Selanjutnya</button>
                    </div>
                </div>
                @endif
            </div>
@endsection