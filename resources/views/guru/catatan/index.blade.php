@extends('layouts.app')

@section('title', 'Daftar Catatan - Guru BK')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Catatan Siswa</h1>
        <a href="{{ route('guru.catatan.buat') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            + Buat Catatan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Isi Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru BK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($catatan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->nama_siswa }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($item->isi, 100) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->guru_bk }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada catatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($catatan, 'hasPages') && $catatan->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $catatan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection