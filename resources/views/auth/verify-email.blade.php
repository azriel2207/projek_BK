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
                <div class="flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-3xl mr-3"></i>
                    <span class="text-2xl font-bold text-white">Verifikasi Email</span>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-8">
                @if(session('resent'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-start">
                        <i class="fas fa-check-circle mr-3 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong>Berhasil!</strong>
                            <p>Link verifikasi baru telah dikirim ke email Anda.</p>
                        </div>
                    </div>
                @endif

                <div class="text-center mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Email Belum Diverifikasi</h3>
                    <p class="text-gray-600 mb-4">
                        Sebelum melanjutkan, silahkan verifikasi email Anda dengan mengklik link yang telah kami kirimkan.
                    </p>
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Link verifikasi dikirim ke: <br>
                        <strong>{{ Auth::user()->email }}</strong>
                    </p>
                </div>

                <!-- Resend Email Form -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>
                        Kirim Ulang Link Verifikasi
                    </button>
                </form>

                <!-- Logout Link -->
                <form method="POST" action="{{ route('logout') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Keluar
                    </button>
                </form>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-gray-700">
                    <p class="mb-2"><i class="fas fa-lightbulb text-yellow-500 mr-2"></i><strong>Tips:</strong></p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Cek folder spam jika email tidak ditemukan</li>
                        <li>Tunggu beberapa menit untuk menerima email</li>
                        <li>Klik link di email untuk menyelesaikan verifikasi</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>Sistem BK Sekolah &copy; 2025</p>
        </div>
    </div>
</body>
</html>
