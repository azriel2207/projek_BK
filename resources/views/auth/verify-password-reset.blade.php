<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Kode Reset - Sistem BK</title>
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
                <i class="fas fa-shield-alt mr-2"></i>Verifikasi Kode
            </h2>
        </div>
        
        <div class="p-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('password.forgot') }}" class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <p class="text-gray-600 text-center mb-2 text-sm">
                Kami telah mengirimkan kode ke
            </p>
            <p class="text-gray-800 text-center font-semibold mb-6">
                {{ $email ?? session('email') }}
            </p>

            <!-- Success Message -->
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-sm">
                <i class="fas fa-check-circle mr-2"></i>Kode reset password telah dikirim ke email Anda. Silakan cek email Anda.
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Anda memiliki 3 kali percobaan untuk memasukkan kode yang benar.
                </p>
            </div>

            <form method="POST" action="{{ route('password.verify-submit') }}">
                @csrf
                
                <!-- Email (Hidden but required) -->
                <input type="hidden" name="email" value="{{ $email ?? session('email') }}">
                
                <!-- Code Input -->
                <div class="mb-6">
                    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-key mr-2 text-blue-600"></i>Masukkan 6 Digit Kode
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        value="{{ old('code') }}"
                        required 
                        autofocus
                        maxlength="6"
                        placeholder="000000"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-center text-2xl font-bold tracking-widest @error('code') border-red-500 @enderror"
                    >
                    @error('code')
                        <p class="text-red-500 text-sm mt-2">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Warning Message -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-warning mr-2"></i>
                        Anda memiliki 3 kali percobaan untuk memasukkan kode yang benar.
                    </p>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                    <i class="fas fa-check mr-2"></i>Verifikasi Kode
                </button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Belum menerima kode?</p>
                <a href="{{ route('password.forgot') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-redo mr-1"></i>Kirim Ulang
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto format code input to numbers only
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    </script>
</body>
</html>
