@extends('layouts.koordinator-layout')

@section('title', 'Tambah Guru BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tambah Guru BK Baru</h1>
            <p class="text-gray-600 mt-2">Isi form berikut untuk menambahkan guru BK baru</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p class="font-bold">Kesalahan Validasi:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('koordinator.guru.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <!-- Nama -->
                <div class="col-span-2">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name') }}" required>
                    @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}" required>
                    @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Telepon</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('phone') }}" required inputmode="numeric" pattern="[0-9]*" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    @error('phone')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- NIP -->
                <div>
                    <label for="nip" class="block text-gray-700 font-bold mb-2">NIP</label>
                    <input type="text" name="nip" id="nip" class="w-full px-4 py-2 border @error('nip') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nip') }}" required inputmode="numeric" pattern="[0-9]*" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    @error('nip')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Spesialisasi -->
                <div>
                    <label for="specialization" class="block text-gray-700 font-bold mb-2">Spesialisasi</label>
                    <input type="text" name="specialization" id="specialization" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('specialization') }}" placeholder="Mis: Konseling Karir">
                </div>

                <!-- Jam Kerja -->
                <div>
                    <label for="office_hours" class="block text-gray-700 font-bold mb-2">Jam Kerja</label>
                    <input type="text" name="office_hours" id="office_hours" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('office_hours') }}" placeholder="Mis: 08:00 - 16:00">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('koordinator.guru.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Kembali
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg transition">
                    Simpan Guru BK
                </button>
            </div>
        </form>
    </div>
</div>
@endsection