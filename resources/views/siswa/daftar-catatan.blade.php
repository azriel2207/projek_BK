@extends('layouts.siswa-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Catatan dari Guru BK</h1>
        <p class="text-gray-600">Baca catatan dan tanggapan dari guru BK</p>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Catatan List -->
    @if($catatan->count() > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru BK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($catatan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->guru_bk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <p class="truncate max-w-xs">{{ $item->isi }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('siswa.catatan.detail', $item->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1">
                                    <i class="fas fa-eye text-sm"></i>
                                    <span>Lihat</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($catatan->hasPages())
        <div class="mt-6">
            {{ $catatan->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Catatan</h3>
            <p class="text-gray-600 mb-6">
                Catatan dari guru BK akan muncul di sini setelah Anda menyelesaikan sesi konseling.
            </p>
            <a href="{{ route('siswa.janji-konseling') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-plus"></i>
                <span>Buat Janji Konseling</span>
            </a>
        </div>
    @endif
</div>
@endsection
