@extends('layouts.koordinator-layout')

@section('title', 'Upgrade ke Guru BK')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-tie text-blue-600 mr-3"></i>Upgrade ke Guru BK
            </h1>
            <p class="text-gray-600 mt-1">Ubah status siswa menjadi guru BK dengan data lengkap</p>
        </div>
        <a href="{{ route('koordinator.siswa.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Data Siswa
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-start">
            <i class="fas fa-check-circle mr-3 mt-0.5"></i>
            <div>
                <strong>Berhasil!</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-start">
            <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
            <div>
                <strong>Error!</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>Form Upgrade Guru BK
                    </h2>
                </div>

                <div class="p-6">
                    <!-- Current User Data -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-circle text-blue-600 mr-2"></i>Data User Saat Ini
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 font-semibold">Nama</p>
                                <p class="text-gray-900 font-bold">{{ $user->name }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 font-semibold">Email</p>
                                <p class="text-gray-900 font-bold">{{ $user->email }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 font-semibold">Role</p>
                                <div class="flex items-center mt-1 space-x-2">
                                    <span class="inline-block px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-sm font-bold">
                                        {{ $user->role }}
                                    </span>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <span class="inline-block px-3 py-1 bg-green-200 text-green-800 rounded-full text-sm font-bold">
                                        guru_bk
                                    </span>
                                </div>
                            </div>
                            @if($user->student)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 font-semibold">NIS / Kelas</p>
                                <p class="text-gray-900 font-bold">{{ $user->student->nis ?? 'N/A' }} / {{ $user->student->kelas ?? 'N/A' }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upgrade Form -->
                    <form action="{{ route('koordinator.siswa.upgrade', $user->id) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-blue-600 mr-2"></i>NIP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip" id="nip" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nip') border-red-500 @enderror" 
                                       value="{{ old('nip') }}" 
                                       placeholder="Contoh: 198506152015021001" required>
                                <p class="text-xs text-gray-500 mt-1">NIP harus unik dan belum terdaftar</p>
                                @error('nip')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Specialization -->
                            <div>
                                <label for="specialization" class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-briefcase text-blue-600 mr-2"></i>Spesialisasi <span class="text-red-500">*</span>
                                </label>
                                <select name="specialization" id="specialization" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('specialization') border-red-500 @enderror" required>
                                    <option value="">-- Pilih Spesialisasi --</option>
                                    <option value="Bimbingan Pribadi" {{ old('specialization') == 'Bimbingan Pribadi' ? 'selected' : '' }}>Bimbingan Pribadi</option>
                                    <option value="Bimbingan Belajar" {{ old('specialization') == 'Bimbingan Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                                    <option value="Bimbingan Karir" {{ old('specialization') == 'Bimbingan Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                                    <option value="Bimbingan Sosial" {{ old('specialization') == 'Bimbingan Sosial' ? 'selected' : '' }}>Bimbingan Sosial</option>
                                    <option value="Bimbingan Multipel" {{ old('specialization') == 'Bimbingan Multipel' ? 'selected' : '' }}>Bimbingan Multipel</option>
                                </select>
                                @error('specialization')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="mb-6">
                            <label for="office_hours" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>Jam Kerja <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="office_hours" id="office_hours" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('office_hours') border-red-500 @enderror" 
                                   value="{{ old('office_hours') }}" 
                                   placeholder="Contoh: Senin-Jumat, 08:00-15:00" required>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jadwal kerja yang akan ditampilkan kepada siswa</p>
                            @error('office_hours')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warning Alert -->
                        <div class="mb-6 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-lg mr-3 mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-yellow-900 mb-2">⚠️ Perhatian Penting!</h4>
                                    <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                                        <li>User <strong>{{ $user->name }}</strong> akan diubah dari <strong>Siswa</strong> menjadi <strong>Guru BK</strong></li>
                                        <li>Data siswa (NIS, kelas, dll) akan <strong>dihapus permanen</strong></li>
                                        <li>User akan mendapatkan akses penuh sebagai Guru BK</li>
                                        <li>Proses ini <strong>tidak dapat dibatalkan setelah dikonfirmasi</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition flex items-center justify-center shadow-md" 
                                    onclick="confirmUpgradeToGuru('{{ addslashes($user->name) }}'); return false;">                                <i class="fas fa-check mr-2"></i>Konfirmasi Upgrade
                            </button>
                            <a href="{{ route('koordinator.siswa.index') }}" class="flex-1 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition flex items-center justify-center shadow-md">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>Informasi Upgrade
                    </h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Upgrade Process -->
                    <div>
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-list-ol text-blue-600 mr-2"></i>Proses Upgrade
                        </h4>
                        <ol class="space-y-2 text-sm text-gray-700 pl-4">
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold mr-2 flex-shrink-0">1</span>
                                <span>Verifikasi data user</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold mr-2 flex-shrink-0">2</span>
                                <span>Input data guru BK (NIP, spesialisasi, jam kerja)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold mr-2 flex-shrink-0">3</span>
                                <span>Hapus data siswa terkait</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold mr-2 flex-shrink-0">4</span>
                                <span>Update role menjadi 'guru_bk'</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold mr-2 flex-shrink-0">5</span>
                                <span>Buat record guru BK baru</span>
                            </li>
                        </ol>
                    </div>

                    <hr class="text-gray-300">

                    <!-- New Access Rights -->
                    <div>
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-unlock text-blue-600 mr-2"></i>Hak Akses Baru
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Melihat dashboard guru BK</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Mengelola jadwal konseling</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Menerima permintaan konseling</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Membuat catatan konseling</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Melihat laporan statistik</span>
                            </li>
                        </ul>
                    </div>

                    <div class="p-3 bg-blue-50 rounded-lg text-xs text-blue-800 flex items-start">
                        <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>User dapat langsung mengakses fitur guru BK setelah upgrade selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection