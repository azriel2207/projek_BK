@extends('layouts.master')

@section('styles')
<style>
    /* Override sidebar color for siswa to blue - matching guru BK */
    .sidebar {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }
</style>
@endsection

@section('sidebar')
    <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-blue-700 transition {{ Route::currentRouteName() === 'siswa.dashboard' ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
    </a>
    <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 hover:bg-blue-700 transition {{ Route::currentRouteName() === 'siswa.janji-konseling' ? 'active' : '' }}">
        <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
    </a>
    <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-blue-700 transition {{ Route::currentRouteName() === 'siswa.riwayat-konseling' ? 'active' : '' }}">
        <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
    </a>
    <a href="{{ route('siswa.catatan.index') }}" class="block py-3 px-6 hover:bg-blue-700 transition {{ Route::currentRouteName() === 'siswa.catatan.index' ? 'active' : '' }}">
        <i class="fas fa-sticky-note mr-3"></i>Catatan dari Guru BK
    </a>
    <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-700 transition {{ Route::currentRouteName() === 'profile' ? 'active' : '' }}">
        <i class="fas fa-user-cog mr-3"></i>Profile Settings
    </a>
@endsection

@section('content')
    @yield('page-content')
@endsection
