@extends('layouts.wali-kelas-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('wali_kelas.detail-siswa', $student->id) }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Data Diri Siswa</h1>
        <p class="text-gray-600 mt-2">{{ $student->nama_lengkap }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-8 max-w-3xl">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wali_kelas.data-diri.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Data Pribadi -->
            <fieldset class="mb-8 pb-8 border-b">
                <legend class="text-lg font-semibold text-gray-800 mb-4">Data Pribadi</legend>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="nama_lengkap" 
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap', $student->nama_lengkap) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>

                    <div>
                        <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            name="tgl_lahir" 
                            id="tgl_lahir"
                            value="{{ old('tgl_lahir', $student->tgl_lahir?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas
                        </label>
                        <input 
                            type="text" 
                            name="kelas" 
                            id="kelas"
                            value="{{ old('kelas', $student->kelas) }}"
                            placeholder="Contoh: X RPL"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            No. HP
                        </label>
                        <input 
                            type="text" 
                            name="no_hp" 
                            id="no_hp"
                            value="{{ old('no_hp', $student->no_hp) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea 
                        name="alamat" 
                        id="alamat"
                        rows="3"
                        class="w-full px-4 py-2 border rounded-lg"
                    >{{ old('alamat', $student->alamat) }}</textarea>
                </div>
            </fieldset>

            <!-- Data Orang Tua -->
            <fieldset class="mb-8 pb-8 border-b">
                <legend class="text-lg font-semibold text-gray-800 mb-4">Data Keluarga</legend>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ayah
                        </label>
                        <input 
                            type="text" 
                            name="nama_ayah" 
                            id="nama_ayah"
                            value="{{ old('nama_ayah', $student->identity->nama_ayah ?? '') }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>

                    <div>
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ibu
                        </label>
                        <input 
                            type="text" 
                            name="nama_ibu" 
                            id="nama_ibu"
                            value="{{ old('nama_ibu', $student->identity->nama_ibu ?? '') }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan Ayah
                        </label>
                        <input 
                            type="text" 
                            name="pekerjaan_ayah" 
                            id="pekerjaan_ayah"
                            value="{{ old('pekerjaan_ayah', $student->identity->pekerjaan_ayah ?? '') }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>

                    <div>
                        <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan Ibu
                        </label>
                        <input 
                            type="text" 
                            name="pekerjaan_ibu" 
                            id="pekerjaan_ibu"
                            value="{{ old('pekerjaan_ibu', $student->identity->pekerjaan_ibu ?? '') }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>
            </fieldset>

            <!-- Data Tambahan -->
            <fieldset class="mb-8">
                <legend class="text-lg font-semibold text-gray-800 mb-4">Data Tambahan</legend>

                <div class="mb-4">
                    <label for="no_induk" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Induk Lainnya
                    </label>
                    <input 
                        type="text" 
                        name="no_induk" 
                        id="no_induk"
                        value="{{ old('no_induk', $student->identity->no_induk ?? '') }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>

                <div class="mb-4">
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Lahir
                    </label>
                    <input 
                        type="text" 
                        name="tempat_lahir" 
                        id="tempat_lahir"
                        value="{{ old('tempat_lahir', $student->identity->tempat_lahir ?? '') }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>

                <div>
                    <label for="catatan_khusus" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Khusus
                    </label>
                    <textarea 
                        name="catatan_khusus" 
                        id="catatan_khusus"
                        rows="3"
                        class="w-full px-4 py-2 border rounded-lg"
                    >{{ old('catatan_khusus', $student->identity->catatan_khusus ?? '') }}</textarea>
                </div>
            </fieldset>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('wali_kelas.detail-siswa', $student->id) }}" 
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
