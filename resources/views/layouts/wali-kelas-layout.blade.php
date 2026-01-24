@extends('layouts.master')

@section('sidebar')
    <a href="{{ route('wali_kelas.dashboard') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'wali_kelas.dashboard' ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
    </a>
    <a href="{{ route('wali_kelas.daftar-siswa') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'wali_kelas.daftar-siswa' ? 'active' : '' }}">
        <i class="fas fa-list mr-3"></i>Daftar Siswa
    </a>
    <a href="{{ route('wali_kelas.create-siswa') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'wali_kelas.create-siswa' ? 'active' : '' }}">
        <i class="fas fa-user-plus mr-3"></i>Tambah Siswa Baru    </a>
    <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-blue-600 transition {{ Route::currentRouteName() === 'profile' ? 'active' : '' }}">
        <i class="fas fa-user-cog mr-3"></i>Profile
    </a>
@endsection

@section('content')
    @yield('page-content')
@endsection