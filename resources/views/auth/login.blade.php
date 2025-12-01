<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- CSRF Token Backup (untuk debugging) -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrfToken">

                    <!-- Email -->
                    <div class="mb-4">
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

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                autocomplete="current-password"
                                placeholder="masukkan password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password') border-red-500 @enderror"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
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
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 focus:ring-4 focus:ring-blue-200 flex items-center justify-center"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span id="btnText">Masuk ke Sistem</span>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Daftar di sini
                        </a>
                    </p>
                </div>

                <!-- Demo Accounts -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800 font-semibold text-center mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Akun Demo
                    </p>
                    <div class="text-xs text-blue-700 space-y-1">
                        <div class="flex items-center justify-between p-2 bg-white rounded">
                            <span>Koordinator BK:</span>
                            <span class="font-mono">bk@gmail.com / 123456</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded">
                            <span>Guru BK:</span>
                            <span class="font-mono">gurubk@gmail.com / 123456</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded">
                            <span>Siswa:</span>
                            <span class="font-mono">siswa@gmail.com / 123456</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            form.addEventListener('submit', function(e) {
                // Validate CSRF token
                const csrfInput = form.querySelector('input[name="_token"]');
                
                if (!csrfInput || !csrfInput.value) {
                    e.preventDefault();
                    alert('Session expired. Halaman akan dimuat ulang.');
                    location.reload();
                    return false;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            });

            // Auto-hide flash messages after 5 seconds (hanya untuk notification)
            setTimeout(function() {
                const alerts = document.querySelectorAll('body > .bg-red-100, body > .bg-green-100');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });

        // Refresh CSRF token setiap 30 menit
        setInterval(function() {
            fetch('/login', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (newToken) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                    document.getElementById('csrfToken').value = newToken;
                    console.log('CSRF token refreshed');
                }
            }).catch(error => {
                console.error('Failed to refresh CSRF token:', error);
            });
        }, 30 * 60 * 1000); // 30 minutes
    </script>
</body>
</html>