@extends('layouts.master')

@section('sidebar')
    <a href="{{ route('guru.dashboard') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
    </a>
    <a href="{{ route('guru.jadwal') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-calendar-alt mr-3"></i>Kelola Jadwal
    </a>
    <a href="{{ route('guru.siswa') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-user-friends mr-3"></i>Daftar Siswa
    </a>
    <a href="{{ route('guru.guru') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-user-tie mr-3"></i>Daftar Guru
    </a>
    <a href="{{ route('guru.riwayat.index') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-file-medical mr-3"></i>Riwayat Konseling
    </a>
    <a href="{{ route('guru.laporan') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-chart-line mr-3"></i>Laporan & Statistik
    </a>
    <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-600 transition">
        <i class="fas fa-user-cog mr-3"></i>Profile Settings
    </a>
@endsection

@section('content')
    @yield('page-content')
@endsection
