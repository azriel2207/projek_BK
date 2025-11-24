@extends('layouts.app')

@section('title', 'Kelola Guru BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Guru BK</h1>
            <p class="text-gray-600 mt-2">Manage counselor/guru BK information</p>
        </div>
        <a href="{{ route('koordinator.guru.create') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg transition">
            + Tambah Guru BK
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form action="{{ route('koordinator.guru.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Cari nama atau email..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                Cari
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NO</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NAMA</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NIP</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">EMAIL</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">TELEPON</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">SPESIALISASI</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ ($gurus->currentPage() - 1) * $gurus->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->nama_lengkap ?? $guru->user->name }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->nip ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->user->email }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $guru->no_hp ?? $guru->user->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                {{ $guru->specialization ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <div class="flex items-center space-x-2">
                                <!-- Tombol View -->
                                <a href="{{ route('koordinator.guru.show', $guru->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="View Detail">
                                    View
                                </a>
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('koordinator.guru.edit', $guru->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="Edit">
                                    Edit
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('koordinator.guru.destroy', $guru->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="Hapus"
                                            onclick="return confirm('Hapus guru BK {{ $guru->nama_lengkap ?? $guru->user->name }}?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data guru BK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $gurus->links() }}
    </div>
</div>
@endsection