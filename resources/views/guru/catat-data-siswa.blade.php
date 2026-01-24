@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('guru.siswa.detail', $student->user_id) }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Catat Data Siswa</h1>
        <p class="text-gray-600 mt-2">{{ $student->nama_lengkap }} ({{ $student->nis }})</p>
    </div>

    <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.siswa.catat-data.store', $student->id) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select 
                    name="kategori" 
                    id="kategori"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Kategori --</option>
                    <option value="akademik" {{ old('kategori') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="perilaku" {{ old('kategori') == 'perilaku' ? 'selected' : '' }}>Perilaku</option>
                    <option value="kesehatan" {{ old('kategori') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="sosial" {{ old('kategori') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="kehadiran" {{ old('kategori') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                    <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Kejadian <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="tanggal_kejadian" 
                    id="tanggal_kejadian"
                    value="{{ old('tanggal_kejadian', date('Y-m-d')) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_kejadian') border-red-500 @enderror"
                >
                @error('tanggal_kejadian')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi"
                    rows="5"
                    required
                    placeholder="Jelaskan kondisi, perilaku, atau kejadian yang diamati..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif (Masih Berlangsung)</option>
                    <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                    <option value="monitoring" {{ old('status') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    <i class="fas fa-save mr-2"></i>Simpan Data
                </button>
                <a 
                    href="{{ route('guru.siswa.detail', $student->user_id) }}" 
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
