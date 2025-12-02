@extends('layouts.guru-layout')

@section('title', 'Edit Profil Saya')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('guru.dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Profil Saya</h1>
            </div>
            <p class="text-gray-600">Update informasi profil dan data pribadi Anda</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p class="font-bold mb-2">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('guru.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user text-blue-600 mr-2"></i>Nama Lengkap *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('name', $user->name) }}" 
                            required
                        >
                        @error('name')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>Email *
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('email', $user->email) }}" 
                            required
                        >
                        @error('email')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone text-blue-600 mr-2"></i>Telepon *
                        </label>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('phone', $user->phone ?? $counselor->no_hp ?? '') }}" 
                            required
                        >
                        @error('phone')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-id-card text-blue-600 mr-2"></i>NIP
                        </label>
                        <input 
                            type="text" 
                            name="nip" 
                            id="nip" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nip') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('nip', $counselor->nip ?? '') }}"
                        >
                        @error('nip')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Spesialisasi -->
                    <div>
                        <label for="specialization" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>Spesialisasi
                        </label>
                        <input 
                            type="text" 
                            name="specialization" 
                            id="specialization" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('specialization') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('specialization', $counselor->specialization ?? '') }}"
                            placeholder="Contoh: Bimbingan Akademik, Karir, etc."
                        >
                        @error('specialization')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jam Kerja -->
                    <div>
                        <label for="office_hours" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>Jam Kerja
                        </label>
                        <input 
                            type="text" 
                            name="office_hours" 
                            id="office_hours" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('office_hours') border-red-500 @else border-gray-300 @enderror" 
                            value="{{ old('office_hours', $counselor->office_hours ?? '08:00 - 16:00') }}"
                            placeholder="Contoh: 08:00 - 16:00"
                        >
                        @error('office_hours')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Section -->
                    <div class="md:col-span-2 border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>Ubah Password
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">Kosongkan jika Anda tidak ingin mengubah password</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-gray-700 font-bold mb-2">Password Baru</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @else border-gray-300 @enderror" 
                            placeholder="Minimum 8 karakter"
                        >
                        @error('password')
                            <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-gray-300"
                        >
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between gap-4 mt-8">
                    <a href="{{ route('profile') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <p class="text-blue-800 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Setiap perubahan data akan secara otomatis disinkronkan ke seluruh sistem.
            </p>
        </div>
    </div>
</div>
@endsection
