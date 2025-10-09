<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-600 py-6 px-8">
            <h2 class="text-2xl font-bold text-white text-center">
                <i class="fas fa-user-plus mr-2"></i>Registrasi Akun Baru
            </h2>
        </div>
        
        <div class="p-8">
            <!-- Tampilkan error jika ada -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- FORM REGISTRASI -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Nama Lengkap
                    </label>
                    <input type="text" name="name" id="name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('name') }}">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                    </label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan email"
                           value="{{ old('email') }}">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        <i class="fas fa-key mr-2 text-blue-600"></i>Password
                    </label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan password (minimal 6 karakter)">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                        <i class="fas fa-key mr-2 text-blue-600"></i>Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan ulang password">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Login di sini
                    </a>
                </p>
            </div>

            <!-- Info -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Informasi
                    </h4>
                    <p class="text-yellow-700 text-sm">
                        Akun yang didaftarkan akan secara otomatis memiliki role <strong>Siswa</strong>. 
                        Untuk akses sebagai Guru BK atau Koordinator BK, hubungi administrator.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
