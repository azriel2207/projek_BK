@extends('layouts.app')

@section('title', 'Laporan & Statistik - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan & Statistik</h1>
        <p class="text-gray-600">Analisis data dan statistik konseling</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <a href="{{ route('guru.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-download"></i>
            <span>Export Laporan</span>
        </button>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-print"></i>
            <span>Print</span>
        </button>
        <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option>Pilih Periode</option>
            <option>Minggu Ini</option>
            <option>Bulan Ini</option>
            <option>Tahun Ini</option>
            <option>Custom</option>
        </select>
    </div>

    <!-- Stats Grid -->
    <div