@extends('layouts.app')

@section('title', 'Dashboard Konselor')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Konselor</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->counselor->nama_lengkap }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Statistik</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span>Sesi Mendatang</span>
                <span class="font-bold text-blue-600">{{ $stats['upcomingSessions'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Total Siswa</span>
                <span class="font-bold text-green-600">{{ $stats['totalStudents'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
        <div class="space-y-2">
            <a href="{{ route('counseling-sessions.create') }}" class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                Buat Sesi Baru
            </a>
            <a href="{{ route('counseling-sessions.index') }}" class="block w-full bg-green-600 text-white text-center py-2 rounded hover:bg-green-700">
                Lihat Sesi Saya
            </a>
        </div>
    </div>
</div>
@endsection
