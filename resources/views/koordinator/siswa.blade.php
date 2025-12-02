@extends('layouts.koordinator-layout')

@section('title', 'Kelola Siswa')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Siswa</h1>
            <p class="text-gray-600 mt-2">Manage student information</p>
        </div>
        <a href="{{ route('koordinator.siswa.create') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg transition">
            + Tambah Siswa
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

    <!-- Debug Info -->
    <div class="mb-4 p-4 bg-blue-100 rounded-lg">
        <p class="text-sm">
            Menampilkan <strong>{{ $siswas->count() }}</strong> dari <strong>{{ $siswas->total() }}</strong> data siswa
        </p>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">NIS</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Kelas</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">No HP</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300 font-medium text-gray-900">
                            {{ $siswa->nama_lengkap ?? $siswa->user->name }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $siswa->nis ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $siswa->user->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $siswa->kelas ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            {{ $siswa->no_hp ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <div class="flex items-center space-x-2">
                                <!-- Tombol Detail -->
                                <a href="{{ route('koordinator.siswa.show', $siswa->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="Detail Siswa">
                                    View
                                </a>
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('koordinator.siswa.edit', $siswa->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition"
                                   title="Edit Siswa">
                                    Edit
                                </a>

                                <!-- Tombol Upgrade (hanya untuk role siswa) -->
                                @if($siswa->user->role === 'siswa')
                                <a href="{{ route('koordinator.siswa.upgrade-form', $siswa->user->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition" 
                                   title="Upgrade ke Guru BK"
                                   onclick="confirmUpgradeStudent('{{ addslashes($siswa->nama_lengkap ?? $siswa->user->name) }}'); return false;">
                                    Upgrade
                                </a>
                                @endif

                                <!-- Tombol Hapus -->
                                <form action="{{ route('koordinator.siswa.destroy', $siswa->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="Hapus Siswa"
                                            onclick="confirmDelete('{{ addslashes($siswa->nama_lengkap ?? $siswa->user->name) }}'); return false;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <p class="text-gray-500 text-lg">Tidak ada data siswa</p>
                                <a href="{{ route('koordinator.siswa.create') }}" 
                                   class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    + Tambah Siswa Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($siswas->hasPages())
    <div class="mt-6">
        {{ $siswas->links() }}
    </div>
    @endif
</div>
@endsection