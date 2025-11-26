@extends('layouts.guru')
@section('title', 'Tambah Jadwal Konseling')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Tambah Jadwal Konseling</h1>
            <p class="text-sm text-slate-500 mt-1">Buat janji konseling untuk siswa Anda.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('guru.jadwal') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border rounded-lg shadow-sm p-6">
        <form action="{{ route('guru.jadwal.simpan') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Jika view dipanggil untuk specific siswa, $selectedSiswa tersedia --}}
            @if(isset($selectedSiswa))
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">{{ $selectedSiswa->name }}</div>
                        <div class="text-xs text-slate-400">ID: {{ $selectedSiswa->id }} — {{ $selectedSiswa->email }}</div>
                        <input type="hidden" name="user_id" value="{{ $selectedSiswa->id }}">
                    </div>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Siswa</label>
                    <select name="user_id" class="w-full rounded-md border px-4 py-2 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Siswa (opsional)</option>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} — {{ $s->email }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Waktu Mulai</label>
                    <input type="time" name="mulai" value="{{ old('mulai') }}" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Waktu Selesai</label>
                    <input type="time" name="selesai" value="{{ old('selesai') }}" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Bimbingan</label>
                    <select name="jenis_bimbingan" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Bimbingan</option>
                        <option value="Pribadi" {{ old('jenis_bimbingan') == 'Pribadi' ? 'selected' : '' }}>Pribadi</option>
                        <option value="Belajar" {{ old('jenis_bimbingan') == 'Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                        <option value="Karir" {{ old('jenis_bimbingan') == 'Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                        <option value="Keluarga" {{ old('jenis_bimbingan') == 'Keluarga' ? 'selected' : '' }}>Bimbingan Keluarga</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status (opsional)</label>
                    <select name="status" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="dikonfirmasi" {{ old('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Keluhan / Deskripsi</label>
                <textarea name="keluhan" rows="4" class="w-full rounded-md border px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan topik atau keluhan singkatnya...">{{ old('keluhan') }}</textarea>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-md border text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection