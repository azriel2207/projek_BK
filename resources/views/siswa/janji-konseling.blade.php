<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janji Konseling - Sistem BK</title>
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
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">Janji Konseling</h2>
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

            <!-- Buat Janji Baru -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Buat Janji Konseling Baru</h2>
                    <button id="toggleForm" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-plus mr-2"></i>Janji Baru
                    </button>
                </div>

                <!-- Form Janji Konseling -->
                <div id="formJanji" class="hidden bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <form method="POST" action="{{ route('siswa.janji-konseling.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Jenis Bimbingan *
                                </label>
                                <select name="jenis_bimbingan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('jenis_bimbingan') border-red-500 @enderror" required>
                                    <option value="">Pilih Jenis Bimbingan</option>
                                    <option value="pribadi" @old('jenis_bimbingan') == 'pribadi' ? 'selected' : '' @endold>Bimbingan Pribadi</option>
                                    <option value="belajar" @old('jenis_bimbingan') == 'belajar' ? 'selected' : '' @endold>Bimbingan Belajar</option>
                                    <option value="karir" @old('jenis_bimbingan') == 'karir' ? 'selected' : '' @endold>Bimbingan Karir</option>
                                    <option value="sosial" @old('jenis_bimbingan') == 'sosial' ? 'selected' : '' @endold>Bimbingan Sosial</option>
                                </select>
                                @error('jenis_bimbingan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tie mr-2 text-purple-600"></i>Guru BK
                                </label>
                                <select name="guru_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('guru_id') border-red-500 @enderror">
                                    <option value="">Pilih Guru BK (Opsional - akan dipilih oleh koordinator)</option>
                                    @if(isset($gurus) && $gurus->count() > 0)
                                        @foreach($gurus as $guru)
                                            <option value="{{ $guru->id }}" @old('guru_id') == $guru->id ? 'selected' : '' @endold>
                                                {{ $guru->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Tidak ada guru BK tersedia</option>
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
                                <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}" required>
                                @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-purple-600"></i>Waktu *
                                </label>
                                <select name="waktu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('waktu') border-red-500 @enderror" required>
                                    <option value="">Pilih Waktu</option>
                                    <option value="08:00 - 09:00" @old('waktu') == '08:00 - 09:00' ? 'selected' : '' @endold>08:00 - 09:00</option>
                                    <option value="09:00 - 10:00" @old('waktu') == '09:00 - 10:00' ? 'selected' : '' @endold>09:00 - 10:00</option>
                                    <option value="10:00 - 11:00" @old('waktu') == '10:00 - 11:00' ? 'selected' : '' @endold>10:00 - 11:00</option>
                                    <option value="13:00 - 14:00" @old('waktu') == '13:00 - 14:00' ? 'selected' : '' @endold>13:00 - 14:00</option>
                                    <option value="14:00 - 15:00" @old('waktu') == '14:00 - 15:00' ? 'selected' : '' @endold>14:00 - 15:00</option>
                                    <option value="15:00 - 16:00" @old('waktu') == '15:00 - 16:00' ? 'selected' : '' @endold>15:00 - 16:00</option>
                                </select>
                                @error('waktu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-dots mr-2 text-purple-600"></i>Keluhan / Permasalahan *
                            </label>
                            <textarea name="keluhan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('keluhan') border-red-500 @enderror" placeholder="Jelaskan permasalahan yang ingin dikonsultasikan..." required>{{ old('keluhan') }}</textarea>
                            @error('keluhan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" id="batalForm" class="px-6 py-2 text-gray-600 hover:text-gray-800 transition font-medium">Batal</button>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition font-medium flex items-center">
                                <i class="fas fa-calendar-check mr-2"></i>Buat Janji
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Janji Menunggu Konfirmasi -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-hourglass-half mr-2 text-yellow-600"></i>Janji Menunggu Konfirmasi
                </h2>
                
                @if(isset($janjiMenunggu) && $janjiMenunggu->count() > 0)
                <div class="space-y-4">
                    @foreach($janjiMenunggu as $janji)
                    <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-100 transition">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($janji->jenis_bimbingan) }}
                                </span>
                                <span class="text-sm text-gray-600">Guru BK</span>
                                <span class="bg-yellow-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Menunggu Konfirmasi
                                </span>
                            </div>
                            <p class="text-gray-700 mb-2 text-sm">{{ Str::limit($janji->keluhan, 150) }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($janji->tanggal)->translatedFormat('d M Y') }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $janji->waktu }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('siswa.janji-konseling.edit', $janji->id) }}" class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-lg text-sm hover:bg-yellow-200 transition flex items-center">
                                <i class="fas fa-edit mr-1"></i>Ubah
                            </a>
                            <form action="{{ route('siswa.janji-konseling.destroy', $janji->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm hover:bg-red-200 transition flex items-center" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji ini?')">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">Tidak ada janji yang menunggu konfirmasi</p>
                </div>
                @endif
            </div>

            <!-- Daftar Janji Terkonfirmasi -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>Janji yang Dikonfirmasi
                </h2>
                
                @if(isset($janjiKonfirmasi) && $janjiKonfirmasi->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Jenis Bimbingan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Guru BK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($janjiKonfirmasi as $janji)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ \Carbon\Carbon::parse($janji->tanggal)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $janji->waktu }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ ucfirst($janji->jenis_bimbingan) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">Guru BK</td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('siswa.janji-konseling.edit', $janji->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-edit mr-1"></i>Ubah
                                    </a>
                                    <form action="{{ route('siswa.janji-konseling.destroy', $janji->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin membatalkan?')">
                                            <i class="fas fa-trash mr-1"></i>Batal
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">Belum ada janji yang dikonfirmasi</p>
                </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Toggle form janji baru
        document.getElementById('toggleForm').addEventListener('click', function() {
            const form = document.getElementById('formJanji');
            form.classList.toggle('hidden');
        });

        document.getElementById('batalForm').addEventListener('click', function() {
            document.getElementById('formJanji').classList.add('hidden');
        });

        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>