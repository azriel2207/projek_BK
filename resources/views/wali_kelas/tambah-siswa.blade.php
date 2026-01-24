@extends('layouts.wali-kelas-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('wali_kelas.daftar-siswa') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Siswa ke Kelas</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
        <form action="{{ route('wali_kelas.tambah-siswa.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Siswa
                </label>
                <select 
                    name="student_id" 
                    id="student_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('student_id') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaAvailable as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->nama_lengkap }} ({{ $s->nis }}) - {{ $s->user->email }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    Tambah Siswa
                </button>
                <a 
                    href="{{ route('wali_kelas.daftar-siswa') }}" 
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
