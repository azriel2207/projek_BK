<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Sistem BK Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center py-8">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 py-6 px-8">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-envelope text-white text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-white">Verifikasi Email</span>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-8 text-center">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="mb-6">
                    <i class="fas fa-envelope-open-text text-blue-500 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Periksa Email Anda</h3>
                    <p class="text-gray-600">
                        Kami telah mengirimkan kode verifikasi ke email Anda. 
                        Silakan cek inbox (atau folder spam) dan masukkan kode tersebut untuk mengaktifkan akun.
                    </p>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('verification.form') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 block">
                        <i class="fas fa-key mr-2"></i>Masukkan Kode Verifikasi
                    </a>

                    <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="email" value="{{ old('email') }}">
                        <button type="submit" 
                                class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-redo mr-2"></i>Kirim Ulang Kode
                        </button>
                    </form>

                    <a href="{{ route('login') }}" 
                       class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 px-4 rounded-lg transition duration-300 block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>