@extends('layouts.guru-layout')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Semua Permintaan Konseling</h1>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        @foreach($permintaan as $janji)
        <div class="flex justify-between items-center p-4 border-b">
            <div>
                <h3 class="font-semibold">{{ $janji->user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $janji->jenis_bimbingan_text }}</p>
                <p class="text-sm">{{ \Carbon\Carbon::parse($janji->tanggal)->format('d-m-Y') }} | {{ $janji->waktu }}</p>
            </div>
            <form action="{{ route('guru.permintaan.konfirmasi', $janji->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                    Konfirmasi
                </button>
            </form>
        </div>
        @endforeach
        
        {{ $permintaan->links() }}
    </div>
</div>
@endsection