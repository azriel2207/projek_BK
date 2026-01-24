<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Bimbingan Konseling Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        #forgotPasswordForm .max-w-md {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white bg-opacity-90 backdrop-blur-sm shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-hands-helping text-blue-600 text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-gray-800">Sistem BK Sekolah</span>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="#fitur" class="text-gray-700 hover:text-blue-600 font-medium">Fitur</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-medium">Tentang</a>
                    <a href="#login" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white bg-opacity-95 backdrop-blur-sm">
        <div class="container mx-auto px-4 py-4 space-y-4">
            <a href="#fitur" class="block text-gray-700 hover:text-blue-600 font-medium">Fitur</a>
            <a href="#tentang" class="block text-gray-700 hover:text-blue-600 font-medium">Tentang</a>
            <a href="#login" class="block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-center">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center text-white">
            <h1 class="text-5xl font-bold mb-6">
                Sistem <span class="text-yellow-300">Bimbingan Konseling</span>
            </h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
                Platform digital untuk memudahkan layanan bimbingan dan konseling di sekolah. 
                Dukung perkembangan siswa secara optimal dengan sistem yang terintegrasi.
            </p>
            
            <div class="flex justify-center space-x-4 mb-16">
                <a href="#login" class="bg-white text-blue-600 px-8 py-3 rounded-lg hover:bg-gray-100 transition font-semibold shadow-lg">
                    <i class="fas fa-door-open mr-2"></i>Masuk Sistem
                </a>
                <button type="button" onclick="showRegistrationForm()" class="border border-white text-white px-8 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                </button>
            </div>
        </div>

        <!-- Features Section -->
        <div id="fitur" class="py-16">
            <h2 class="text-3xl font-bold text-center text-white mb-12">Layanan Bimbingan Konseling</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Fitur 1 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl text-center card-hover border border-white border-opacity-20">
                    <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Bimbingan Pribadi</h3>
                    <p class="text-gray-200 leading-relaxed">Membantu siswa memahami diri, menerima diri, dan mengarahkan diri sesuai dengan potensi</p>
                </div>
                
                <!-- Fitur 2 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl text-center card-hover border border-white border-opacity-20">
                    <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Bimbingan Belajar</h3>
                    <p class="text-gray-200 leading-relaxed">Membantu siswa mengembangkan sikap dan kebiasaan belajar yang efektif dan efisien</p>
                </div>
                
                <!-- Fitur 3 -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-2xl text-center card-hover border border-white border-opacity-20">
                    <div class="bg-white bg-opacity-20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-briefcase text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Bimbingan Karir</h3>
                    <p class="text-gray-200 leading-relaxed">Membantu siswa merencanakan dan mengembangkan karir masa depannya</p>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div id="tentang" class="py-16">
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8 max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-white mb-6 text-center">Tentang Sistem BK</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <p class="text-gray-200 text-lg leading-relaxed mb-6">
                            Sistem Bimbingan Konseling Sekolah adalah platform digital yang mendukung 
                            pelaksanaan program BK di sekolah secara efektif dan efisien. Sistem ini 
                            dirancang untuk memudahkan guru BK dalam memberikan layanan kepada siswa.
                        </p>
                        <p class="text-gray-200 text-lg leading-relaxed">
                            Dengan sistem ini, seluruh proses bimbingan dan konseling dapat terdokumentasi 
                            dengan baik, terpantau perkembangannya, dan terlapor secara komprehensif.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-6 inline-block">
                            <i class="fas fa-hands-helping text-white text-6xl mb-4"></i>
                            <h3 class="text-xl font-bold text-white">Layanan Profesional</h3>
                            <p class="text-gray-200 mt-2">Mendukung perkembangan siswa secara holistik</p>
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <div class="bg-blue-500 bg-opacity-20 rounded-xl p-6">
                        <h4 class="text-xl font-bold text-white mb-4 text-center">
                            <i class="fas fa-eye mr-2"></i>Visi
                        </h4>
                        <p class="text-gray-200 text-center">
                            Menjadi sistem pendukung BK yang terdepan dalam memfasilitasi perkembangan 
                            siswa secara optimal dan berkarakter
                        </p>
                    </div>
                    <div class="bg-green-500 bg-opacity-20 rounded-xl p-6">
                        <h4 class="text-xl font-bold text-white mb-4 text-center">
                            <i class="fas fa-bullseye mr-2"></i>Misi
                        </h4>
                        <p class="text-gray-200 text-center">
                            Menyediakan platform digital yang memudahkan pelaksanaan layanan BK 
                            yang komprehensif dan terintegrasi
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login & Forgot Password Section -->
        <div id="login" class="py-16">
            <!-- Login Form -->
            <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden" id="loginCard">
                <div class="bg-blue-600 py-6 px-8">
                    <h2 class="text-2xl font-bold text-white text-center">
                        <i class="fas fa-lock mr-2"></i>Login Sistem BK
                    </h2>
                </div>
                
                <div class="p-8">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
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
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nis">
                                <i class="fas fa-id-card mr-2 text-blue-600"></i>NIS (untuk siswa)
                            </label>
                            <input type="text" name="nis" id="nis" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="masukkan NIS Anda (jika siswa)"
                                   value="{{ old('nis') }}">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                <i class="fas fa-key mr-2 text-blue-600"></i>Password
                            </label>
                            <input type="password" name="password" id="password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="masukkan password">
                            <!-- Lupa Password Link -->
                            <div class="mt-2 text-right">
                                <button type="button" onclick="showForgotPasswordForm()" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">
                                    <i class="fas fa-key mr-1"></i>Lupa password?
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Sistem
                        </button>
                    </form>
                </div>
            </div>

            <!-- Forgot Password Form (Hidden by default) -->
            <div id="forgotPasswordForm" style="display: none;" class="py-16">
                <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-blue-600 py-6 px-8">
                        <h2 class="text-2xl font-bold text-white text-center">
                            <i class="fas fa-key mr-2"></i>Lupa Password?
                        </h2>
                    </div>
                    
                    <div class="p-8">
                        <p class="text-gray-600 text-center text-sm mb-6">
                            Masukkan email Anda dan kami akan mengirimkan kode reset password
                        </p>

                        <form method="POST" action="{{ route('password.send-code') }}" id="forgotPasswordFormElement">
                            @csrf
                            
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="forgotPasswordEmail">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                                </label>
                                <input type="email" name="email" id="forgotPasswordEmail" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="masukkan email anda">
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Kami akan mengirimkan kode verifikasi ke email Anda. Kode ini berlaku selama 15 menit.
                                </p>
                            </div>

                            <button type="button" id="forgotPasswordSubmit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Kode Reset
                            </button>
                        </form>

                        <div class="text-center">
                            <button type="button" onclick="showLoginForm()" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form (Hidden by default) -->
            <div id="registrationForm" style="display: none;" class="py-16">
                <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-blue-600 py-6 px-8">
                        <h2 class="text-2xl font-bold text-white text-center">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Akun Baru
                        </h2>
                    </div>
                    
                    <div class="p-8">
                        <p class="text-gray-600 text-center text-sm mb-6">
                            Buat akun baru untuk mengakses sistem
                        </p>

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registrationFormElement">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    <i class="fas fa-user mr-2 text-blue-600"></i>Nama Lengkap
                                </label>
                                <input type="text" name="name" id="name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="masukkan nama lengkap"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="reg-email">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                                </label>
                                <input type="email" name="email" id="reg-email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="masukkan email anda"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="reg-password">
                                    <i class="fas fa-key mr-2 text-blue-600"></i>Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="reg-password" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                           placeholder="minimal 8 karakter">
                                    <button type="button" onclick="togglePasswordVisibility('reg-password', 'reg-password-icon')" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="reg-password-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="reg-password-confirm">
                                    <i class="fas fa-key mr-2 text-blue-600"></i>Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="reg-password-confirm" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                           placeholder="ulangi password">
                                    <button type="button" onclick="togglePasswordVisibility('reg-password-confirm', 'reg-password-confirm-icon')" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="reg-password-confirm-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="reg-nis">
                                    <i class="fas fa-id-card mr-2 text-blue-600"></i>Nomor Induk Siswa (NIS)
                                </label>
                                <input type="text" name="nis" id="reg-nis" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="masukkan NIS Anda"
                                       value="{{ old('nis') }}">
                                @error('nis')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="reg-kelas">
                                    <i class="fas fa-book mr-2 text-blue-600"></i>Kelas
                                </label>
                                <input type="text" name="kelas" id="reg-kelas" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="contoh: X-A, XI-B"
                                       value="{{ old('kelas') }}">
                                @error('kelas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <input type="hidden" name="role" value="siswa">

                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                                <i class="fas fa-user-check mr-2"></i>Daftar Akun
                            </button>
                        </form>

                        <div class="text-center">
                            <button type="button" onclick="showLoginForm()" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white bg-opacity-10 backdrop-blur-sm py-12 border-t border-white border-opacity-20 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Brand Section -->
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-hands-helping text-white text-3xl mr-3"></i>
                        <span class="text-2xl font-bold text-white">Sistem BK Sekolah</span>
                    </div>
                    <p class="text-gray-300 text-center md:text-left">Platform Layanan Bimbingan Konseling Terintegrasi</p>
                </div>

                <!-- Copyright Section -->
                <div class="flex flex-col items-center md:items-end text-gray-300 text-center md:text-right">
                    <p class="text-sm">&copy; 2024 Sistem Bimbingan Konseling Sekolah</p>
                    <p class="text-xs mt-2 opacity-75">Mendukung Pendidikan Karakter Bangsa</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Forgot Password Modal Functions
        function showForgotPasswordForm() {
            const loginCard = document.getElementById('loginCard').closest('div');
            if (loginCard) {
                loginCard.style.display = 'none';
            }
            document.getElementById('registrationForm').style.display = 'none';
            document.getElementById('forgotPasswordForm').style.display = 'block';
            setTimeout(() => {
                document.getElementById('forgotPasswordForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }

        function showLoginForm() {
            document.getElementById('forgotPasswordForm').style.display = 'none';
            document.getElementById('registrationForm').style.display = 'none';
            const loginCard = document.getElementById('loginCard').closest('div');
            if (loginCard) {
                loginCard.style.display = 'block';
            }
            setTimeout(() => {
                document.getElementById('loginCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }

        function showRegistrationForm() {
            const loginCard = document.getElementById('loginCard').closest('div');
            if (loginCard) {
                loginCard.style.display = 'none';
            }
            document.getElementById('forgotPasswordForm').style.display = 'none';
            document.getElementById('registrationForm').style.display = 'block';
            setTimeout(() => {
                document.getElementById('registrationForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }

        // Toggle Password Visibility
        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Handle forgot password form submission
        document.getElementById('forgotPasswordSubmit')?.addEventListener('click', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('forgotPasswordEmail').value;
            if (emailInput) {
                document.getElementById('forgotPasswordFormElement').submit();
            } else {
                alert('Silakan masukkan email Anda');
            }
        });
    </script>
</body>
</html>