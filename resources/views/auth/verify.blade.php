<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Kode Verifikasi - Sistem BK Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center py-8">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 py-6 px-8">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-key text-white text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-white">Kode Verifikasi</span>
                </div>
            </div>
            
            <!-- Form -->
            <div class="p-8">
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.verify') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                        </label>
                        <input type="email" name="email" id="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="masukkan email yang didaftarkan"
                               value="{{ old('email') }}">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="verification_code">
                            <i class="fas fa-shield-alt mr-2 text-blue-600"></i>Kode Verifikasi (6 digit)
                        </label>
                        <input type="text" name="verification_code" id="verification_code" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-center text-2xl font-mono tracking-widest"
                               placeholder="000000"
                               maxlength="6"
                               pattern="[0-9]{6}">
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                        <i class="fas fa-check-circle mr-2"></i>Verifikasi Akun
                    </button>
                </form>

                <div class="text-center space-y-3">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ old('email') }}">
                        <button type="submit" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            <i class="fas fa-redo mr-1"></i>Kirim ulang kode verifikasi
                        </button>
                    </form>

                    <div>
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke login
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-clock mr-2"></i>Penting!
                    </h4>
                    <p class="text-xs text-yellow-700">
                        Kode verifikasi akan kadaluarsa dalam 24 jam. 
                        Jika tidak menerima email, periksa folder spam atau request kode baru.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto focus dan format input kode verifikasi
        document.getElementById('verification_code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    </script>
</body>
</html>