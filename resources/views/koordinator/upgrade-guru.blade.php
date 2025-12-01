@extends('layouts.koordinator-layout')

@section('title', 'Upgrade ke Guru BK')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upgrade ke Guru BK</h1>
        <a href="{{ route('koordinator.siswa.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Data Siswa
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Upgrade Guru BK</h6>
                </div>
                <div class="card-body">
                    <!-- Info User -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Data User Saat Ini:</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="120"><strong>Nama</strong></td>
                                    <td>: {{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: {{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role</strong></td>
                                    <td>: 
                                        <span class="badge badge-warning">{{ $user->role }}</span>
                                        <i class="fas fa-arrow-right text-muted mx-2"></i>
                                        <span class="badge badge-success">guru_bk</span>
                                    </td>
                                </tr>
                                @if($user->student)
                                <tr>
                                    <td><strong>NIS</strong></td>
                                    <td>: {{ $user->student->nis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>: {{ $user->student->kelas }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Form Upgrade -->
                    <form action="{{ route('koordinator.siswa.upgrade', $user->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nip" class="font-weight-bold">NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip" id="nip" 
                                           class="form-control @error('nip') is-invalid @enderror" 
                                           value="{{ old('nip') }}" 
                                           placeholder="Masukkan NIP" required>
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">NIP harus unik dan belum terdaftar</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="specialization" class="font-weight-bold">Spesialisasi <span class="text-danger">*</span></label>
                                    <select name="specialization" id="specialization" 
                                            class="form-control @error('specialization') is-invalid @enderror" required>
                                        <option value="">Pilih Spesialisasi</option>
                                        <option value="Bimbingan Pribadi" {{ old('specialization') == 'Bimbingan Pribadi' ? 'selected' : '' }}>Bimbingan Pribadi</option>
                                        <option value="Bimbingan Belajar" {{ old('specialization') == 'Bimbingan Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                                        <option value="Bimbingan Karir" {{ old('specialization') == 'Bimbingan Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                                        <option value="Bimbingan Sosial" {{ old('specialization') == 'Bimbingan Sosial' ? 'selected' : '' }}>Bimbingan Sosial</option>
                                        <option value="Bimbingan Multipel" {{ old('specialization') == 'Bimbingan Multipel' ? 'selected' : '' }}>Bimbingan Multipel</option>
                                    </select>
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="office_hours" class="font-weight-bold">Jam Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="office_hours" id="office_hours" 
                                   class="form-control @error('office_hours') is-invalid @enderror" 
                                   value="{{ old('office_hours') }}" 
                                   placeholder="Contoh: Senin-Jumat, 08:00-15:00" required>
                            @error('office_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan jadwal kerja yang akan ditampilkan kepada siswa</small>
                        </div>

                        <!-- Warning Alert -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Perhatian!</h6>
                            <ul class="mb-0 pl-3">
                                <li>User <strong>{{ $user->name }}</strong> akan diubah dari <strong>Siswa</strong> menjadi <strong>Guru BK</strong></li>
                                <li>Data siswa (NIS, kelas, dll) akan <strong>dihapus permanen</strong></li>
                                <li>User akan mendapatkan akses penuh sebagai Guru BK</li>
                                <li>Proses ini <strong>tidak dapat dibatalkan</strong></li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg" 
                                    onclick="return confirm('Apakah Anda yakin ingin meng-upgrade {{ $user->name }} menjadi Guru BK?')">
                                <i class="fas fa-user-graduate"></i> Konfirmasi Upgrade ke Guru BK
                            </button>
                            <a href="{{ route('koordinator.siswa.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Upgrade</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h5>Upgrade Role User</h5>
                    </div>
                    <hr>
                    <div class="small">
                        <p><strong>Proses Upgrade:</strong></p>
                        <ol>
                            <li>Verifikasi data user</li>
                            <li>Input data guru BK (NIP, spesialisasi, jam kerja)</li>
                            <li>Hapus data siswa terkait</li>
                            <li>Update role menjadi 'guru_bk'</li>
                            <li>Buat record guru BK baru</li>
                        </ol>
                        
                        <p><strong>Hak Akses Baru:</strong></p>
                        <ul>
                            <li>Melihat dashboard guru BK</li>
                            <li>Mengelola jadwal konseling</li>
                            <li>Menerima permintaan konseling</li>
                            <li>Membuat catatan konseling</li>
                            <li>Melihat laporan statistik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection