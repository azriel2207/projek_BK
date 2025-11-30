<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Janji Konseling - Sistem BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { transition: all 0.3s ease; }
        .main-content { margin-left: 16rem; }
        @media (max-width: 768px) {
            .sidebar { margin-left: -16rem; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-purple-700 text-white">
        <div class="p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hands-helping text-2xl"></i>
                <h1 class="text-xl font-bold">Sistem BK</h1>
            </div>
        </div>
        
        <nav class="mt-8">
            <a href="{{ route('siswa.dashboard') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="{{ route('siswa.janji-konseling') }}" class="block py-3 px-6 bg-purple-600 border-l-4 border-yellow-400">
                <i class="fas fa-calendar-check mr-3"></i>Janji Konseling
            </a>
            <a href="{{ route('siswa.riwayat-konseling') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-file-alt mr-3"></i>Riwayat Konseling
            </a>
            <a href="{{ route('siswa.bimbingan-belajar') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-graduation-cap mr-3"></i>Bimbingan Belajar
            </a>
            <a href="{{ route('siswa.bimbingan-karir') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-briefcase mr-3"></i>Bimbingan Karir
            </a>
            <a href="{{ route('profile') }}" class="block py-3 px-6 hover:bg-purple-600 transition">
                <i class="fas fa-user-cog mr-3"></i>Profile Settings
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-purple-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-3 text-red-300 hover:text-red-100 transition w-full">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center p-4">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Edit Janji Konseling</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Breadcrumb -->
            <div class="mb-6 text-sm text-gray-600">
                <a href="{{ route('siswa.janji-konseling') }}" class="text-blue-600 hover:text-blue-800">Janji Konseling</a>
                <span class="mx-2">→</span>
                <span>Edit Janji</span>
            </div>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Edit -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>Form Edit Janji Konseling
                </h2>

                <form method="POST" action="{{ route('siswa.janji-konseling.update', $janji->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Jenis Bimbingan *
                            </label>
                            <select name="jenis_bimbingan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('jenis_bimbingan') border-red-500 @enderror" required>
                                <option value="">Pilih Jenis Bimbingan</option>
                                <option value="pribadi" @if($janji->jenis_bimbingan == 'pribadi') selected @endif>Bimbingan Pribadi</option>
                                <option value="belajar" @if($janji->jenis_bimbingan == 'belajar') selected @endif>Bimbingan Belajar</option>
                                <option value="karir" @if($janji->jenis_bimbingan == 'karir') selected @endif>Bimbingan Karir</option>
                                <option value="sosial" @if($janji->jenis_bimbingan == 'sosial') selected @endif>Bimbingan Sosial</option>
                            </select>
                            @error('jenis_bimbingan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tie mr-2 text-purple-600"></i>Guru BK
                            </label>
                            <select name="guru_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('guru_id') border-red-500 @enderror">
                                <option value="">Pilih Guru BK (Opsional)</option>
                                @if(isset($gurus) && $gurus->count() > 0)
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" @if($janji->guru_id == $guru->id) selected @endif>
                                            {{ $guru->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('guru_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-day mr-2 text-purple-600"></i>Tanggal Konseling *
                            </label>
                            <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror" value="{{ $janji->tanggal->format('Y-m-d') }}" required>
                            @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-purple-600"></i>Waktu *
                            </label>
                            <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('waktu') border-red-500 @enderror" required>
                                <option value="">Pilih Waktu</option>
                                <option value="08:00 - 09:00" @if($janji->waktu == '08:00 - 09:00') selected @endif>08:00 - 09:00</option>
                                <option value="09:00 - 10:00" @if($janji->waktu == '09:00 - 10:00') selected @endif>09:00 - 10:00</option>
                                <option value="10:00 - 11:00" @if($janji->waktu == '10:00 - 11:00') selected @endif>10:00 - 11:00</option>
                                <option value="13:00 - 14:00" @if($janji->waktu == '13:00 - 14:00') selected @endif>13:00 - 14:00</option>
                                <option value="14:00 - 15:00" @if($janji->waktu == '14:00 - 15:00') selected @endif>14:00 - 15:00</option>
                                <option value="15:00 - 16:00" @if($janji->waktu == '15:00 - 16:00') selected @endif>15:00 - 16:00</option>
                            </select>
                            @error('waktu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-dots mr-2 text-purple-600"></i>Keluhan / Permasalahan *
                        </label>
                        <textarea name="keluhan" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('keluhan') border-red-500 @enderror" placeholder="Jelaskan permasalahan yang ingin dikonsultasikan..." required>{{ $janji->keluhan }}</textarea>
                        @error('keluhan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Status Info -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Status Saat Ini:</strong> 
                            <span class="font-semibold">
                                @if($janji->status == 'menunggu')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Menunggu Konfirmasi</span>
                                @elseif($janji->status == 'dikonfirmasi')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Dikonfirmasi</span>
                                @elseif($janji->status == 'selesai')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Selesai</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">{{ ucfirst($janji->status) }}</span>
                                @endif
                            </span>
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('siswa.janji-konseling') }}" class="px-6 py-3 text-gray-600 hover:text-gray-800 transition font-medium border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition font-medium flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h3 class="text-sm font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Catatan Penting
                </h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Anda hanya bisa mengubah janji yang masih berstatus "Menunggu Konfirmasi"</li>
                    <li>• Tanggal tidak boleh di masa lalu</li>
                    <li>• Minimal 10 karakter untuk deskripsi keluhan</li>
                </ul>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
