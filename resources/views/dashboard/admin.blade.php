@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
        Dashboard Admin
    </h1>
    <p class="text-gray-600 flex items-center">
        <i class="fas fa-user mr-2"></i>
        Selamat datang, {{ auth()->user()->username }}
    </p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-600">Total Siswa</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['totalStudents'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-user-tie text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-600">Total Konselor</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['totalCounselors'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-600">Total Sesi</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['totalSessions'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-600">Menunggu Konfirmasi</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pendingSessions'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4 flex items-center">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Menu Cepat
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('students.index') }}" class="bg-blue-50 p-4 rounded-lg text-center hover:bg-blue-100 transition border border-blue-200">
            <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
            <p class="font-medium text-blue-800">Kelola Siswa</p>
            <p class="text-sm text-blue-600 mt-1">Manage data siswa</p>
        </a>
        
        <a href="{{ route('counselors.index') }}" class="bg-green-50 p-4 rounded-lg text-center hover:bg-green-100 transition border border-green-200">
            <i class="fas fa-user-tie text-green-600 text-2xl mb-2"></i>
            <p class="font-medium text-green-800">Kelola Konselor</p>
            <p class="text-sm text-green-600 mt-1">Manage konselor</p>
        </a>
        
        <a href="{{ route('counseling-sessions.index') }}" class="bg-purple-50 p-4 rounded-lg text-center hover:bg-purple-100 transition border border-purple-200">
            <i class="fas fa-calendar-alt text-purple-600 text-2xl mb-2"></i>
            <p class="font-medium text-purple-800">Sesi Konseling</p>
            <p class="text-sm text-purple-600 mt-1">Kelola sesi</p>
        </a>
        
        <a href="#" class="bg-yellow-50 p-4 rounded-lg text-center hover:bg-yellow-100 transition border border-yellow-200">
            <i class="fas fa-chart-bar text-yellow-600 text-2xl mb-2"></i>
            <p class="font-medium text-yellow-800">Laporan</p>
            <p class="text-sm text-yellow-600 mt-1">Generate laporan</p>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4 flex items-center">
        <i class="fas fa-history text-gray-600 mr-2"></i>
        Aktivitas Terbaru
    </h3>
    <div class="space-y-3">
        <div class="flex items-center p-3 bg-gray-50 rounded">
            <i class="fas fa-user-plus text-green-500 mr-3"></i>
            <div>
                <p class="font-medium">Siswa baru terdaftar</p>
                <p class="text-sm text-gray-600">2 siswa baru ditambahkan hari ini</p>
            </div>
        </div>
        <div class="flex items-center p-3 bg-gray-50 rounded">
            <i class="fas fa-calendar-check text-blue-500 mr-3"></i>
            <div>
                <p class="font-medium">Sesi konseling baru</p>
                <p class="text-sm text-gray-600">3 sesi ditambahkan minggu ini</p>
            </div>
        </div>
    </div>
</div>
@endsection