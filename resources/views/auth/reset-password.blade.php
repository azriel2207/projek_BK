<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Sistem BK</title>
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
                <i class="fas fa-lock mr-2"></i>Password Baru
            </h2>
        </div>
        
        <div class="p-8">
            <p class="text-gray-600 text-center mb-6 text-sm">
                Masukkan password baru yang aman untuk akun Anda
            </p>

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

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-xs font-semibold text-blue-800 mb-2">💡 Tips Password Kuat:</p>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li>• Minimal 8 karakter</li>
                    <li>• Gunakan huruf besar dan kecil</li>
                    <li>• Sertakan angka dan simbol</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="masukkan password baru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            oninput="checkPasswordStrength()"
                        >
                        <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex gap-1">
                            <div class="h-1 flex-1 bg-gray-300 rounded" id="strength1"></div>
                            <div class="h-1 flex-1 bg-gray-300 rounded" id="strength2"></div>
                            <div class="h-1 flex-1 bg-gray-300 rounded" id="strength3"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="strengthText">Password harus minimal 8 karakter</p>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            autocomplete="new-password"
                            placeholder="konfirmasi password baru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password_confirmation') border-red-500 @enderror"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 mb-4">
                    <i class="fas fa-save mr-2"></i>Simpan Password Baru
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('password.forgot') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strength1 = document.getElementById('strength1');
            const strength2 = document.getElementById('strength2');
            const strength3 = document.getElementById('strength3');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password) && /[^a-zA-Z0-9]/.test(password)) strength++;

            // Reset colors
            strength1.className = 'h-1 flex-1 bg-gray-300 rounded';
            strength2.className = 'h-1 flex-1 bg-gray-300 rounded';
            strength3.className = 'h-1 flex-1 bg-gray-300 rounded';

            if (strength === 1) {
                strength1.className = 'h-1 flex-1 bg-red-500 rounded';
                strengthText.textContent = 'Password lemah';
                strengthText.className = 'text-xs text-red-500 mt-1';
            } else if (strength === 2) {
                strength1.className = 'h-1 flex-1 bg-yellow-500 rounded';
                strength2.className = 'h-1 flex-1 bg-yellow-500 rounded';
                strengthText.textContent = 'Password sedang';
                strengthText.className = 'text-xs text-yellow-600 mt-1';
            } else if (strength === 3) {
                strength1.className = 'h-1 flex-1 bg-green-500 rounded';
                strength2.className = 'h-1 flex-1 bg-green-500 rounded';
                strength3.className = 'h-1 flex-1 bg-green-500 rounded';
                strengthText.textContent = 'Password kuat';
                strengthText.className = 'text-xs text-green-600 mt-1';
            } else {
                strengthText.textContent = 'Password harus minimal 8 karakter';
                strengthText.className = 'text-xs text-gray-500 mt-1';
            }
        }
    </script>
</body>
</html>
