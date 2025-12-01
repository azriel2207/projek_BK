@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Email</h1>
                <p class="text-gray-600">Kami telah mengirimkan kode verifikasi 6 digit ke email Anda</p>
            </div>

            <!-- Email Display -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 text-center">
                <p class="text-sm text-gray-600 mb-1">Email:</p>
                <p class="text-lg font-semibold text-gray-900">{{ Auth::user()->email }}</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Verification Form -->
            <form action="{{ route('verification.submit') }}" method="POST" class="mb-6">
                @csrf

                <!-- Code Input -->
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-3">
                        Masukkan Kode Verifikasi
                    </label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        inputmode="numeric"
                        maxlength="6"
                        placeholder="000000"
                        class="w-full px-4 py-3 text-center text-2xl font-bold tracking-widest border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition @error('code') border-red-500 @enderror"
                        value="{{ old('code') }}"
                        autocomplete="off"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-2">Kode terdiri dari 6 angka</p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-105"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verifikasi Email
                    </span>
                </button>
            </form>

            <!-- Resend Form -->
            <div class="border-t pt-6">
                <p class="text-sm text-gray-600 mb-4 text-center">Tidak menerima kode?</p>
                <form action="{{ route('verification.resend') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition duration-200"
                    >
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Kirim Ulang Kode
                        </span>
                    </button>
                </form>
            </div>

            <!-- Info Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700 font-semibold">Informasi</p>
                        <p class="text-xs text-blue-600 mt-1">Kode verifikasi berlaku selama 15 menit. Jangan bagikan kode ini kepada siapapun.</p>
                    </div>
                </div>
            </div>

            <!-- Logout Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Bukan akun Anda?
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Logout
                        </button>
                    </form>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-8">
            © 2025 Sistem BK Sekolah. All rights reserved.
        </p>
    </div>
</div>

<!-- Auto-format input to digits only -->
<script>
    document.getElementById('code').addEventListener('input', function(e) {
        // Remove non-digit characters
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
</script>
@endsection
