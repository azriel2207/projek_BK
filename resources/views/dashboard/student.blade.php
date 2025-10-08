@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->student->nama_lengkap }}</p>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4">Informasi Saya</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p><strong>NIS:</strong> {{ auth()->user()->student->nis }}</p>
            <p><strong>Kelas:</strong> {{ auth()->user()->student->kelas }}</p>
        </div>
        <div>
            <p><strong>Sesi Mendatang:</strong> {{ $stats['upcomingSessions'] }}</p>
            <a href="{{ route('counseling-sessions.create') }}" class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Request Konseling
            </a>
        </div>
    </div>
</div>
@endsection