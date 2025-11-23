<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Left Side - Vision & Mission -->
    <div class="hidden lg:flex lg:w-1/2 gradient-bg text-white p-12 flex-col justify-center">
        <div class="max-w-md mx-auto">
            <!-- Logo -->
            <div class="flex items-center space-x-3 mb-8">
                <i class="fas fa-hands-helping text-3xl"></i>
                <h1 class="text-2xl font-bold">Sistem BK</h1>
            </div>

            <!-- Vision -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Visi</h2>
                <p class="text-lg leading-relaxed">
                    Menjadi sistem pendukung BK yang terdepan dalam memfasilitasi perkembangan siswa 
                    secara optimal dan berkarakter
                </p>
            </div>

            <!-- Mission -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Misi</h2>
                <p class="text-lg leading-relaxed">
                    Menyediakan platform digital yang memudahkan pelaksanaan layanan BK yang 
                    komprehensif dan terintegrasi
                </p>
            </div>

            <!-- Divider -->
            <div class="border-t border-white/30 my-8"></div>

            <!-- Features -->
            <div class="space-y-2 text-white/80">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span>Konseling Online</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span>Manajemen Siswa</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span>Laporan Terintegrasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 bg-gray-50 flex items-center justify-center p-8">
        <div class="max-w-md w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-center space-x-3 mb-8">
                <i class="fas fa-hands-helping text-3xl text-blue-600"></i>
                <h1 class="text-2xl font-bold text-gray-800">Sistem BK</h1>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Login Sistem BK</h2>
                <p class="text-gray-600 text-center mb-6">Masuk ke akun Anda</p>

                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <!-- Tambahkan hidden token manual untuk backup -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            placeholder="masukkan email anda"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="masukkan password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection (TAMBAHKAN INI) -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Login Sebagai
                        </label>
                        <select 
                            id="role" 
                            name="role" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        >
                            <option value="">Pilih Role</option>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru BK</option>
                            <option value="koordinator" {{ old('role') == 'koordinator' ? 'selected' : '' }}>Koordinator BK</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 focus:ring-4 focus:ring-blue-200"
                    >
                        Masuk ke Sistem
                    </button>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Daftar di sini
                        </a>
                    </p>
                </div>

                <!-- Demo Accounts -->
                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-600 text-center mb-2">Akun Demo:</p>
                    <div class="text-xs text-gray-500 space-y-1">
                        <div>Koordinator BK: bk@gmail.com / 123456</div>
                        <div>Guru BK: gurubk@gmail.com / 123456</div>
                        <div>Siswa: siswa@gmail.com / 123456</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>