@extends('layouts.app')

@section('title', 'Kelola Jadwal Konseling - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Jadwal Konseling</h1>
        <p class="text-gray-600">Kelola jadwal konseling siswa Anda</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-plus"></i>
            <span>Tambah Jadwal</span>
        </button>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-calendar-alt"></i>
            <span>Kalender View</span>
        </button>
    </div>

    <!-- Jadwal Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Jadwal Konseling</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Topik
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwal as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_siswa }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->email_siswa }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->session_date)->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->session_date)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->topic }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                    'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                    'berlangsung' => 'bg-green-100 text-green-800',
                                    'selesai' => 'bg-gray-100 text-gray-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                                $statusText = [
                                    'menunggu_konfirmasi' => 'Menunggu',
                                    'dijadwalkan' => 'Terjadwal',
                                    'berlangsung' => 'Berlangsung',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusText[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 transition duration-150">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 transition duration-150">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900 transition duration-150">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-3xl mb-2 block"></i>
                            Belum ada jadwal konseling
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwal->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $jadwal->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Jadwal</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $jadwal->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ DB::table('counseling_sessions')->where('counselor_id', auth()->id())->where('status', 'selesai')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Menunggu</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ DB::table('counseling_sessions')->where('counselor_id', auth()->id())->where('status', 'menunggu_konfirmasi')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Dibatalkan</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ DB::table('counseling_sessions')->where('counselor_id', auth()->id())->where('status', 'dibatalkan')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // JavaScript untuk interaksi jadwal
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan fungsi untuk modal dan interaksi di sini
        console.log('Jadwal management loaded');
    });
</script>
@endsection