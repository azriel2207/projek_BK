<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-600 py-6 px-8">
            <h2 class="text-2xl font-bold text-white text-center">
                <i class="fas fa-key mr-2"></i>Lupa Password?
            </h2>
        </div>
        
        <div class="p-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('login') }}" class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <p class="text-gray-600 text-center mb-6 text-sm">
                Masukkan email Anda dan kami akan mengirimkan kode reset password
            </p>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.send-code') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        autocomplete="email"
                        placeholder="masukkan email anda"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Info Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Kami akan mengirimkan kode verifikasi ke email Anda. Kode ini berlaku selama 15 menit.
                    </p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 focus:ring-4 focus:ring-blue-200 flex items-center justify-center"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Kode Reset
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-6">
                <p class="text-gray-600 text-sm">
                    Sudah ingat password Anda? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
