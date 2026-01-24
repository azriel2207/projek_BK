@extends('layouts.wali-kelas-layout')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Siswa Kelas</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola daftar siswa yang Anda bimbing</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama atau NIS..."
                value="{{ request('search') }}"
                class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-500"
            >
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 font-medium">
                <i class="fas fa-search mr-1"></i>Cari
            </button>
        </form>
    </div>

    <!-- Siswa Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No.</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Siswa</th>
                        <th class="px-4 py-3 text-left font-semibold">NIS</th>
                        <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                        <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700 font-medium">{{ $siswa->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $s->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $s->nis }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $s->kelas ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs break-words">{{ $s->user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('wali_kelas.detail-siswa', $s->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-medium"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('wali_kelas.data-diri', $s->id) }}" 
                                       class="text-green-600 hover:text-green-800 hover:underline text-xs font-medium"
                                       title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">
                                <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                                <p>Belum ada siswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Info & Controls -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs text-gray-600 whitespace-nowrap">
                    Showing <span class="font-semibold text-gray-800">{{ $siswa->firstItem() ?? 0 }}</span> to <span class="font-semibold text-gray-800">{{ $siswa->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-800">{{ $siswa->total() }}</span> results
                </div>
                @if($siswa->hasPages())
                    {{ $siswa->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

