@php
    // Default layout dan route
    $layout = 'layouts.master';
    $updateRoute = route('profile.update'); // default route
    $method = 'POST'; // default method

    if (Auth::user()->isGuruBK()) {
        $layout = 'layouts.guru-layout';
        $updateRoute = route('guru.profile.update'); // route guru
        $method = 'PUT'; // untuk method spoofing
    } elseif (Auth::user()->isSiswa()) {
        $layout = 'layouts.siswa-layout';
        $updateRoute = route('profile.update'); // route siswa / user biasa
        $method = 'POST'; // sesuai route
    } else {
        $layout = 'layouts.koordinator-layout';
        // Kalau ada koordinator update route, ganti di sini
        // $updateRoute = route('koordinator.profile.update');
        $method = 'POST';
    }
@endphp

@extends($layout)

@section('title', 'Profile Settings - Sistem BK')

@section('page-content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
            <h1 class="text-2xl font-bold">📝 Profile Settings</h1>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $updateRoute }}" method="POST" class="space-y-6">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Ganti Password -->
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🔒 Ganti Password</h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengganti password</p>

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                            <input type="password" name="current_password" id="current_password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="new_password" id="new_password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                    <a href="{{ url('/dashboard') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium text-center transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
