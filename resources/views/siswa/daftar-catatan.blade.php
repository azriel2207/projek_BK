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

    <!-- Catatan List - Card Grid Layout -->
    @if($catatan->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($catatan as $item)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-l-4 border-l-blue-500">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Guru BK</h3>
                                <p class="text-lg font-bold text-blue-700 mt-1">{{ $item->guru_bk }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-6 py-4 space-y-4">
                        <!-- Catatan Section -->
                        <div>
                            <h4 class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <i class="fas fa-notebook text-blue-500"></i>
                                Catatan
                            </h4>
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-2 bg-blue-50 p-3 rounded">
                                @php
                                    $parts = explode('--- REKOMENDASI ---', $item->isi ?? '');
                                    echo Str::limit(trim($parts[0]), 120, '...');
                                @endphp
                            </p>
                        </div>

                        <!-- Rekomendasi Section -->
                        <div>
                            <h4 class="text-xs font-medium text-yellow-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <i class="fas fa-lightbulb text-yellow-500"></i>
                                Rekomendasi
                            </h4>
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-2 bg-yellow-50 p-3 rounded">
                                @php
                                    if(count($parts) > 1) {
                                        echo Str::limit(trim($parts[1]), 120, '...');
                                    } else {
                                        echo '<span class="text-gray-500 italic">Tidak ada rekomendasi khusus</span>';
                                    }
                                @endphp
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="bg-gray-50 px-6 py-3 flex justify-end border-t border-gray-200">
                        <a href="{{ route('siswa.catatan.detail', $item->id) }}" 
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($catatan->hasPages())
        <div class="mt-10">
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
