@extends('layouts.guru-layout')

@section('page-content')
<div class="container py-6">
    <h3>Atur Kelas - {{ $siswa->name }}</h3>

    <form method="POST" action="{{ route('guru.siswa.kelas.update', $siswa->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label>Kelas</label>
            <input type="text" name="kelas" value="{{ old('kelas', $kelas) }}" class="form-control" placeholder="Contoh: X-IPA-1" />
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('guru.siswa.show', $siswa->id) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection