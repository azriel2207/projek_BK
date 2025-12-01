@extends('layouts.koordinator-layout')

@section('page-content')
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Guru BK</h1>
                    <p class="text-gray-600">Kelola data guru bimbingan dan konseling</p>
                </div>
                <a href="{{ route('koordinator.guru.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition font-medium">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Guru BK</span>
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total Guru BK</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('users')->where('role', 'guru_bk')->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Konseling Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ DB::table('janji_konselings')->whereMonth('tanggal', now()->month)->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-comments text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Rata-rata per Guru</p>
                            @php
                                $totalGuru = DB::table('users')->where('role', 'guru_bk')->count();
                                $totalKonseling = DB::table('janji_konselings')->count();
                                $rataRata = $totalGuru > 0 ? round($totalKonseling / $totalGuru) : 0;
                            @endphp
                            <p class="text-2xl font-bold text-gray-800">{{ $rataRata }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="mb-6">
                <form method="GET" action="{{ route('koordinator.guru.index') }}" class="mb-6">
                    <div class="flex gap-3 items-center flex-wrap">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="flex-1 min-w-[250px] rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Cari guru berdasarkan nama, email, atau NIP..." />

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-3 rounded-lg inline-flex items-center gap-2 transition font-medium">
                            <i class="fas fa-search"></i> Cari
                        </button>

                        @if(request('search'))
                            <a href="{{ route('koordinator.guru.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg inline-flex items-center gap-2 transition font-medium">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Guru BK Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Guru BK</h3>
                    @if(request('search'))
                        <p class="text-sm text-gray-600">Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></p>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Guru</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konseling</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa Dibimbing</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($gurus as $guru)
                            @php
                                $jumlahKonseling = DB::table('janji_konselings')->where('guru_bk', $guru->id)->count();
                                $siswaDibimbing = DB::table('janji_konselings')->where('guru_bk', $guru->id)->distinct('user_id')->count('user_id');
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ ($gurus->currentPage() - 1) * $gurus->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $guru->name ?? $guru->nama_lengkap }}</div>
                                            <div class="text-sm text-gray-500">Guru BK</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $guru->nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $guru->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $guru->no_hp ?? $guru->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $guru->specialization ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $jumlahKonseling }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $siswaDibimbing }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('koordinator.guru.edit', $guru->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('koordinator.guru.destroy', $guru->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-user-tie text-5xl mb-3 text-gray-300"></i>
                                        <p class="text-gray-500 font-medium mb-1">Guru BK tidak ditemukan</p>
                                        @if(request('search'))
                                            <p class="text-sm text-gray-400">Tidak ada hasil untuk pencarian "<strong>{{ request('search') }}</strong>"</p>
                                            <a href="{{ route('koordinator.guru.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-3">Lihat semua guru BK</a>
                                        @else
                                            <p class="text-sm text-gray-400">Belum ada data guru BK terdaftar</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($gurus->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $gurus->links() }}
                </div>
                @endif
            </div>
@endsection