@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-users mr-2 text-blue-600"></i>
        Data Siswa
    </h1>
    <a href="{{ route('students.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Siswa
    </a>
</div>

<!-- Search and Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" placeholder="Cari siswa..." class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <select class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="">Semua Kelas</option>
                <option value="X">Kelas X</option>
                <option value="XI">Kelas XI</option>
                <option value="XII">Kelas XII</option>
            </select>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
            <i class="fas fa-search mr-2"></i>
            Cari
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-id-card mr-1"></i>
                    NIS
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-user mr-1"></i>
                    Nama Lengkap
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    Kelas
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-envelope mr-1"></i>
                    Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-cog mr-1"></i>
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($students as $student)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-id-card text-blue-600"></i>
                        </div>
                        <span class="ml-3 font-medium">{{ $student->nis }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <span class="ml-3">{{ $student->nama_lengkap }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        {{ $student->kelas }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                    {{ $student->user->email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <a href="{{ route('students.show', $student) }}" 
                       class="text-blue-600 hover:text-blue-900 flex items-center"
                       title="Lihat Detail">
                        <i class="fas fa-eye mr-1"></i>
                        Lihat
                    </a>
                    <a href="{{ route('students.edit', $student) }}" 
                       class="text-green-600 hover:text-green-900 flex items-center"
                       title="Edit Data">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900 flex items-center"
                                onclick="return confirm('Hapus siswa {{ $student->nama_lengkap }}?')"
                                title="Hapus Siswa">
                            <i class="fas fa-trash mr-1"></i>
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Empty State -->
    @if($students->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900">Belum ada data siswa</h3>
        <p class="text-gray-500 mt-2">Mulai dengan menambahkan siswa pertama Anda.</p>
        <a href="{{ route('students.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Siswa Pertama
        </a>
    </div>
    @endif
</div>

<!-- Pagination -->
@if($students->hasPages())
<div class="mt-6 bg-white px-4 py-3 rounded-lg shadow">
    {{ $students->links() }}
</div>
@endif
@endsection