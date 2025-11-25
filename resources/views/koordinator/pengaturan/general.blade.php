@extends('koordinator.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pengaturan Umum</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('koordinator.pengaturan.general.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informasi Sekolah</h6>
                                
                                <div class="form-group mb-3">
                                    <label for="school_name" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" id="school_name" name="school_name" 
                                           value="{{ old('school_name', $schoolName) }}" required>
                                    @error('school_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="school_address" class="form-label">Alamat Sekolah</label>
                                    <textarea class="form-control" id="school_address" name="school_address" rows="3" required>{{ old('school_address', $schoolAddress) }}</textarea>
                                    @error('school_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="academic_year" class="form-label">Tahun Ajaran</label>
                                    <input type="text" class="form-control" id="academic_year" name="academic_year" 
                                           value="{{ old('academic_year', $academicYear) }}" required>
                                    @error('academic_year')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Konfigurasi Sistem</h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="email_notification" name="email_notification" 
                                           value="1" {{ $emailNotification ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notification">
                                        Notifikasi Email - Kirim notifikasi via email
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup" 
                                           value="1" {{ $autoBackup ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_backup">
                                        Auto Backup - Backup otomatis
                                    </label>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="backup_frequency" class="form-label">Frekuensi Backup</label>
                                    <select class="form-control" id="backup_frequency" name="backup_frequency">
                                        <option value="daily" {{ $backupFrequency == 'daily' ? 'selected' : '' }}>Setiap Hari</option>
                                        <option value="weekly" {{ $backupFrequency == 'weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                                        <option value="monthly" {{ $backupFrequency == 'monthly' ? 'selected' : '' }}>Setiap Bulan</option>
                                    </select>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                           value="1" {{ $maintenanceMode ? 'checked' : '' }}>
                                    <label class="form-check-label" for="maintenance_mode">
                                        Maintenance Mode - Nonaktifkan akses sementara
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            
                            <button type="button" class="btn btn-secondary" onclick="resetSettings()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            
                            <a href="{{ route('koordinator.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                    
                    <!-- Reset Form -->
                    <form id="resetForm" action="{{ route('koordinator.pengaturan.reset') }}" method="POST" style="display: none;">
                        @csrf
                        @method('POST')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetSettings() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan ke nilai default?')) {
        document.getElementById('resetForm').submit();
    }
}
</script>
@endsection