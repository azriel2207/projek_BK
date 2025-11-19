@extends('layouts.app')

@section('title', 'Kelola Guru BK - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Guru BK</h1>
        <p class="text-gray-600">Manajemen data guru bimbingan konseling</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('koordinator.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <a href="{{ route('koordinator.guru.tambah') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-plus"></i>
            <span>Tambah Guru BK</span>
        </a>
        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 transition">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-user-tie text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Guru BK</p>
                <p class="text-2xl font-bold text-gray-900">{{ $guruBK->total() }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $guruBK->total() }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Siswa Terlayani</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ DB::table('janji_konselings')->distinct('user_id')->count('user_id') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Guru BK</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($guruBK as $guru)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $guru->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $guru->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">{{ $guru->email }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">{{ $guru->phone ?? '-' }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            {{ \Carbon\Carbon::parse($guru->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('koordinator.guru.edit', $guru->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator.guru.hapus', $guru->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin hapus guru ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-user-slash text-4xl mb-3 block text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada data Guru BK</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guruBK->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $guruBK->links() }}
        </div>
        @endif
    </div>
</div>
@endsection