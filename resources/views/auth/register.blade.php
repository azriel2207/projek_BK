<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <i class="fas fa-hands-helping text-3xl text-blue-600"></i>
                <h1 class="text-3xl font-bold text-gray-800">Sistem Bimbingan Konseling</h1>
            </div>
            <p class="text-gray-600">Daftarkan akun untuk mengakses layanan bimbingan dan konseling</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Registrasi -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
                <p class="text-gray-600 mb-6">Isi form berikut untuk mendaftar</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required 
                            autofocus
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Alamat Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Role -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-badge mr-2 text-blue-500"></i>Pilih Jenis Akun
                        </label>
                        <select 
                            id="role" 
                            name="role" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('role') border-red-500 @enderror"
                        >
                            <option value="">-- Pilih Jenis Akun --</option>
                            <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>
                                👨‍🎓 Siswa (Akses Konseling)
                            </option>
                            <option value="guru_bk" {{ old('role') === 'guru_bk' ? 'selected' : '' }}>
                                👨‍🏫 Guru BK (Kelola Konseling)
                            </option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="Buat password yang kuat"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password') border-red-500 @enderror"
                            >
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                placeholder="Masukkan ulang password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            >
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-blue-200"
                    >
                        <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                    </button>
                </form>

                <!-- Login Link - DIUBAH: Kembali ke Landing Page -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>Kembali ke Halaman Utama
                        </a>
                    </p>
                </div>
            </div>

            <!-- Informasi Panel -->
            <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg p-8 text-white">
                <div class="flex items-center space-x-3 mb-6">
                    <i class="fas fa-info-circle text-2xl"></i>
                    <h3 class="text-xl font-bold">Informasi Registrasi</h3>
                </div>

                <!-- Role Information -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">Tipe Akun yang Tersedia:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-blue-500/20 rounded-lg">
                            <i class="fas fa-user-graduate text-lg"></i>
                            <div>
                                <p class="font-semibold">Siswa</p>
                                <p class="text-blue-100 text-sm">Akses default untuk konseling</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-purple-500/20 rounded-lg">
                            <i class="fas fa-user-tie text-lg"></i>
                            <div>
                                <p class="font-semibold">Guru BK</p>
                                <p class="text-purple-100 text-sm">Kelola sesi konseling siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-indigo-500/20 rounded-lg">
                            <i class="fas fa-user-shield text-lg"></i>
                            <div>
                                <p class="font-semibold">Koordinator BK</p>
                                <p class="text-indigo-100 text-sm">Monitor seluruh sistem BK</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-white/10 rounded-lg p-4 border-l-4 border-green-400">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-300 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-green-300 mb-1">Informasi</h4>
                            <p class="text-blue-100 text-sm">
                                Pilih jenis akun saat registrasi sesuai dengan kebutuhan Anda. 
                                Akun <strong>Guru BK</strong> dapat langsung mengelola konseling siswa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Features List -->
                <div class="mt-6">
                    <h4 class="font-semibold mb-3">Fitur yang Didapat:</h4>
                    <ul class="space-y-2 text-blue-100">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-300"></i>
                            <span>Janji konseling online</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-300"></i>
                            <span>Riwayat bimbingan</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-300"></i>
                            <span>Konsultasi pribadi</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-300"></i>
                            <span>Bimbingan belajar & karir</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
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
    </script>
</body>
</html>