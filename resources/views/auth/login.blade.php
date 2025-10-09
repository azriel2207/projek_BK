<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-600 py-6 px-8">
            <h2 class="text-2xl font-bold text-white text-center">
                <i class="fas fa-lock mr-2"></i>Login Sistem BK
            </h2>
        </div>
        
        <div class="p-8">
            <!-- Tampilkan error jika ada -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                    </label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="masukkan email anda"
                           value="{{ old('email') }}">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        <i class="fas fa-key mr-2 text-blue-600"></i>Password
                    </label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="masukkan password">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Sistem
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Daftar di sini
                    </a>
                </p>
            </div>

            <!-- Info akun demo -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-600 mb-3 text-center">Akun Demo:</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium">Koordinator BK:</span>
                        <span>bk@gmail.com / 123456</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Guru BK:</span>
                        <span>gurubk@gmail.com / 123456</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Siswa:</span>
                        <span>siswa@gmail.com / 123456</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
