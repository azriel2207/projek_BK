@extends('layouts.master')

@section('sidebar')
    <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
    </a>
    <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
    </a>
    <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
    </a>
    <a href="{{ route('siswa.catatan.index') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-sticky-note mr-3"></i>Catatan dari Guru BK
    </a>
    <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
    </a>
    <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-briefcase mr-3"></i>Bimbingan Karir
    </a>
    <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
        <i class="fas fa-user-cog mr-3"></i>Profile Settings
    </a>
@endsection

@section('content')
    @yield('page-content')
@endsection
