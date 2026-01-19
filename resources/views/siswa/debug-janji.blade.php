<!-- Debug Page untuk Cek Janji Data -->
@extends('layouts.siswa-layout')

@section('page-content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Debug Janji Konseling</h1>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">User Info</h2>
        <p>Authenticated User: {{ Auth::id() }} - {{ Auth::user()->name ?? 'N/A' }}</p>
        
        <h2 class="text-lg font-semibold mt-6 mb-4">All Janji in Database for This User</h2>
        
        @php
            $allJanji = \App\Models\JanjiKonseling::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        @endphp
        
        <p class="mb-4">Total Janji: {{ $allJanji->count() }}</p>
        
        @if($allJanji->count() > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">User ID</th>
                        <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                        <th class="border border-gray-300 px-4 py-2">Waktu</th>
                        <th class="border border-gray-300 px-4 py-2">Jenis Bimbingan</th>
                        <th class="border border-gray-300 px-4 py-2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allJanji as $janji)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span class="px-2 py-1 rounded text-white
                                @if($janji->status === 'menunggu') bg-yellow-500
                                @elseif($janji->status === 'dikonfirmasi') bg-blue-500
                                @elseif($janji->status === 'selesai') bg-green-500
                                @elseif($janji->status === 'dibatalkan') bg-red-500
                                @endif
                            ">{{ $janji->status }}</span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->user_id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->tanggal }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->waktu }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->jenis_bimbingan }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $janji->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Tidak ada janji</p>
        @endif
    </div>
</div>
@endsection
