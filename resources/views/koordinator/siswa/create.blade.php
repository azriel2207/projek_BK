@extends('layouts.koordinator-layout')

@section('title', 'Tambah Siswa Baru')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Tambah Siswa Baru</h1>
                <a href="{{ route('koordinator.siswa.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Error</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('koordinator.siswa.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('name') }}" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('email') }}" required>
                        </div>

                        <!-- NIS -->
                        <div>
                            <label for="nis" class="block text-gray-700 font-bold mb-2">NIS</label>
                            <input type="text" name="nis" id="nis" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('nis') }}" required inputmode="numeric" pattern="[0-9]*" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tgl_lahir" class="block text-gray-700 font-bold mb-2">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('tgl_lahir') }}" required>
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-4">
                        <!-- Kelas -->
                        <div>
                            <label for="kelas" class="block text-gray-700 font-bold mb-2">Kelas</label>
                            <input type="text" name="kelas" id="kelas" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('kelas') }}" required>
                        </div>

                        <!-- No HP -->
                        <div>
                            <label for="no_hp" class="block text-gray-700 font-bold mb-2">No HP</label>
                            <input type="text" name="no_hp" id="no_hp" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('no_hp') }}" required inputmode="numeric" pattern="[0-9]*" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-6">
                    <label for="alamat" class="block text-gray-700 font-bold mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required>{{ old('alamat') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('koordinator.siswa.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        Simpan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection