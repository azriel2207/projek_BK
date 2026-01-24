@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-center text-gray-800">Verifikasi NIS</h1>
                <p class="text-center text-gray-600 mt-2">
                    Silakan masukkan NIS Anda untuk melanjutkan
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('verification.nis.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                        NIS (Nomor Induk Siswa)
                    </label>
                    <input 
                        type="text" 
                        id="nis" 
                        name="nis" 
                        placeholder="Masukkan NIS Anda"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                        autofocus
                    >
                    <p class="text-sm text-gray-500 mt-1">
                        NIS Anda adalah: <strong>{{ $student->nis ?? 'tidak ditemukan' }}</strong>
                    </p>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Verifikasi NIS
                </button>

                <p class="text-center text-gray-600 text-sm">
                    Cek email atau hubungi sekolah jika lupa NIS Anda
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
