@extends('layouts.guru-layout')

@section('title', 'Daftar Guru BK - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Guru BK</h1>
        <p class="text-gray-600">Kelola data guru BK yang terdaftar di sistem</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('guru.guru') }}" class="mb-6">
            <div class="flex gap-3 items-center">
                <input type="text" name="q" value="{{ request('q') }}"
                       class="w-full rounded-lg border px-4 py-3"
                       placeholder="Cari guru berdasarkan nama atau email..." />

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg inline-flex items-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Guru Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Data Guru BK</h2>
            <p class="text-sm text-gray-600">Total {{ $daftarGuru->total() }} guru terdaftar</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Bergabung
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
                    @forelse($daftarGuru as $guru)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $guru->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $guru->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $guru->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $guru->role === 'guru_bk' ? 'Guru BK' : 'Guru' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($guru->created_at)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-900 transition duration-150 flex items-center space-x-1">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat</span>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-900 transition duration-150 flex items-center space-x-1">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            <p class="font-medium">Belum ada guru yang terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($daftarGuru->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $daftarGuru->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
