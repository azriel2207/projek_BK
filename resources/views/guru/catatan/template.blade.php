@extends('layouts.app')

@section('title', 'Template Catatan - Sistem BK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Template Catatan Konseling</h1>
        <p class="text-gray-600">Pilih template untuk memudahkan pembuatan catatan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-blue-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">📚 Akademik</h3>
            <p class="text-gray-600 mb-4">Template untuk konseling masalah akademik dan belajar</p>
            <a href="{{ route('guru.catatan.buat') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                Gunakan Template
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-purple-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">💫 Personal</h3>
            <p class="text-gray-600 mb-4">Template untuk konseling masalah pribadi dan perkembangan diri</p>
            <a href="{{ route('guru.catatan.buat') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                Gunakan Template
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-yellow-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">🎯 Karir</h3>
            <p class="text-gray-600 mb-4">Template untuk konseling perencanaan karir dan minat bakat</p>
            <a href="{{ route('guru.catatan.buat') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                Gunakan Template
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-green-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">🤝 Sosial</h3>
            <p class="text-gray-600 mb-4">Template untuk konseling masalah sosial dan hubungan interpersonal</p>
            <a href="{{ route('guru.catatan.buat') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                Gunakan Template
            </a>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('guru.catatan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
            Kembali ke Daftar Catatan
        </a>
    </div>
</div>
@endsection