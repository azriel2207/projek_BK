@extends('layouts.koordinator-layout')

@section('title', 'Detail Guru BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detail Guru BK</h1>
            <a href="{{ route('koordinator.guru.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $guru->nama_lengkap ?? $guru->name }}</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">NIP</p>
                    <p class="text-gray-900 font-semibold">{{ $guru->nip ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-gray-900 font-semibold">{{ $guru->email }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Telepon</p>
                    <p class="text-gray-900 font-semibold">{{ $guru->no_hp ?? $guru->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Spesialisasi</p>
                    <p class="text-gray-900 font-semibold">{{ $guru->specialization ?? 'General' }}</p>
                </div>

                <div class="col-span-2">
                    <p class="text-gray-600 text-sm">Jam Kerja</p>
                    <p class="text-gray-900 font-semibold">{{ $guru->office_hours ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Statistik</h3>

            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Total Sesi Konseling</p>
                    <p class="text-2xl font-bold text-blue-600">
                        @php
                            $konseling = DB::table('janji_konselings')
                                ->where('guru_bk', $guru->name)
                                ->count();
                        @endphp
                        {{ $konseling }}
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Sesi Aktif</p>
                    <p class="text-2xl font-bold text-green-600">
                        @php
                            $aktif = DB::table('janji_konselings')
                                ->where('guru_bk', $guru->name)
                                ->where('status', 'active')
                                ->count();
                        @endphp
                        {{ $aktif }}
                    </p>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Sesi Selesai</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        @php
                            $selesai = DB::table('janji_konselings')
                                ->where('guru_bk', $guru->name)
                                ->where('status', 'completed')
                                ->count();
                        @endphp
                        {{ $selesai }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('koordinator.guru.edit', $guru->id) }}" class="bg-yellow-600 hover:bg-yellow-800 text-white font-bold py-2 px-6 rounded-lg transition">
                Edit
            </a>
            <form action="{{ route('koordinator.guru.destroy', $guru->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-6 rounded-lg transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection