@extends('layouts.master')

@section('sidebar')
    <a href="{{ route('koordinator.dashboard') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'koordinator.dashboard' ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
    </a>
    <a href="{{ route('koordinator.guru.index') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'koordinator.guru.index' ? 'active' : '' }}">
        <i class="fas fa-user-tie mr-3"></i>Kelola Guru BK
    </a>
    <a href="{{ route('koordinator.siswa.index') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'koordinator.siswa.index' ? 'active' : '' }}">
        <i class="fas fa-users mr-3"></i>Data Siswa
    </a>
    <a href="{{ route('koordinator.wali-kelas.index') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'koordinator.wali-kelas.index' ? 'active' : '' }}">
        <i class="fas fa-chalkboard-user mr-3"></i>Kelola Wali Kelas
    </a>
    <a href="{{ route('koordinator.laporan') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'koordinator.laporan' ? 'active' : '' }}">
        <i class="fas fa-chart-bar mr-3"></i>Laporan
    </a>
    <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'profile' ? 'active' : '' }}">
        <i class="fas fa-user-cog mr-3"></i>Profile
    </a>
@endsection

@section('content')
    @yield('page-content')
@endsection
