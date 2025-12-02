@extends('layouts.guru-layout')

@section('title', 'Kelola Jadwal - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Jadwal Konseling</h1>
        <p class="text-gray-600">Kelola jadwal konseling siswa Anda</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <a href="{{ route('guru.jadwal.tambah') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-plus"></i>
            <span>Tambah Jadwal</span>
        </a>
        {{-- Kalender View button removed --}}
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Jadwal</p>
                <p class="text-2xl font-bold text-gray-900">{{ $jadwal->total() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ DB::table('janji_konselings')->where('status', 'selesai')->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Menunggu</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ DB::table('janji_konselings')->where('status', 'menunggu')->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Dibatalkan</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ DB::table('janji_konselings')->where('status', 'dibatalkan')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Jadwal Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Jadwal Konseling</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Topik
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwal as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_siswa }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->email_siswa }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock text-gray-400 mr-1"></i>
                                {{ $item->waktu }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">{{ ucfirst($item->jenis_bimbingan ?? 'Konseling') }}</div>
                            @if($item->keluhan)
                            <div class="text-xs text-gray-500 max-w-xs truncate">{{ Str::limit($item->keluhan, 40) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                    'dikonfirmasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check'],
                                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                    'dibatalkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
                                ];
                                $status = $statusColors[$item->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question'];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                                <i class="fas {{ $status['icon'] }} mr-1"></i>
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex gap-2">
        <!-- View Detail -->
        <a href="{{ route('guru.jadwal.detail', $item->id) }}" 
           class="text-blue-600 hover:text-blue-900 transition" 
           title="Lihat Detail">
            <i class="fas fa-eye"></i>
        </a>
        
        <!-- Edit -->
        <a href="{{ route('guru.jadwal.edit', $item->id) }}" 
           class="text-green-600 hover:text-green-900 transition" 
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        
        <!-- Hapus -->
        <form action="{{ route('guru.jadwal.hapus', $item->id) }}" 
              method="POST" 
              class="inline"
              onsubmit="return confirmDelete('jadwal')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="text-red-600 hover:text-red-900 transition" 
                    title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        
        <!-- Catatan (jika status selesai) -->
        @if($item->status == 'selesai')
        <a href="{{ route('guru.riwayat.detail', $item->id) }}" 
           class="text-purple-600 hover:text-purple-900 transition" 
           title="Lihat Catatan">
            <i class="fas fa-file-alt"></i>
        </a>
        @elseif($item->status == 'dikonfirmasi')
        <!-- Mark as Selesai -->
        <form action="{{ route('guru.permintaan.selesai', $item->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="text-green-600 hover:text-green-900 transition" 
                    title="Mark Selesai"
                    onclick="return confirmComplete(); return false;">
                <i class="fas fa-check-double"></i>
            </button>
        </form>
        @else
        <!-- Tambah Catatan -->
        <a href="{{ route('guru.riwayat.buat') }}" 
           class="text-orange-600 hover:text-orange-900 transition" 
           title="Tambah Catatan">
            <i class="fas fa-notes-medical"></i>
        </a>
        @endif
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-3 block text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada jadwal konseling</p>
                            <p class="text-sm mt-1">Klik tombol "Tambah Jadwal" untuk membuat jadwal baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwal->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $jadwal->links() }}
        </div>
        @endif
    </div>
</div>
@endsection