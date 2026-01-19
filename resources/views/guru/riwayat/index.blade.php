@extends('layouts.guru-layout')

@section('title', 'Riwayat Konseling - Guru BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Konseling Siswa</h1>
        <p class="text-gray-600">Daftar lengkap konseling yang telah selesai</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Catatan Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Catatan Konseling</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Bimbingan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
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
                    @forelse($catatanFiltered as $item)
                    <tr class="hover:bg-gray-50 @if(is_null($item->catatan_id)) bg-yellow-50 @endif">
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->nama_siswa }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                {{ ucfirst($item->jenis_bimbingan ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                {{ ucfirst($item->status ?? 'selesai') }}
                            </span>
                            @if(is_null($item->catatan_id))
                                <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                    Belum ada catatan
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm space-x-2 flex flex-wrap gap-2">
                            @if(!is_null($item->catatan_id))
                                <a href="{{ route('guru.riwayat.detail', $item->catatan_id) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium inline-flex items-center gap-1 px-3 py-1 bg-blue-50 rounded">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat</span>
                                </a>
                                <a href="{{ route('guru.riwayat.edit', $item->catatan_id) }}" 
                                   class="text-orange-600 hover:text-orange-900 font-medium inline-flex items-center gap-1 px-3 py-1 bg-orange-50 rounded">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            @else
                                <a href="{{ route('guru.riwayat.tambah', $item->janji_id) }}" 
                                   class="text-green-600 hover:text-green-900 font-medium inline-flex items-center gap-1 px-3 py-1 bg-green-50 rounded">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Catatan</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                            Belum ada riwayat konseling yang selesai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($catatanFiltered->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $catatanFiltered->links() }}
        </div>
        @endif
    </div>
</div>
@endsection