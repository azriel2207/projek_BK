<form action="{{ route('guru.jadwal.simpan') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="user_id">Siswa</label>
        <select name="user_id" id="user_id" class="form-control" required>
            <option value="">Pilih Siswa</option>
            @foreach($siswa as $s)
                <option value="{{ $s->id }}">{{ $s->name }} - {{ $s->email }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="mulai">Waktu Mulai</label>
                <input type="time" name="mulai" id="mulai" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="selesai">Waktu Selesai</label>
                <input type="time" name="selesai" id="selesai" class="form-control">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="jenis_bimbingan">Jenis Bimbingan</label>
        <select name="jenis_bimbingan" id="jenis_bimbingan" class="form-control" required>
            <option value="">Pilih Jenis Bimbingan</option>
            <option value="pribadi">Bimbingan Pribadi</option>
            <option value="belajar">Bimbingan Belajar</option>
            <option value="karir">Bimbingan Karir</option>
            <option value="sosial">Bimbingan Sosial</option>
        </select>
    </div>

    <div class="form-group">
        <label for="keluhan">Keluhan / Permasalahan</label>
        <textarea name="keluhan" id="keluhan" class="form-control" rows="3" placeholder="Deskripsi keluhan atau permasalahan..."></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
    <a href="{{ route('guru.jadwal') }}" class="btn btn-secondary">Batal</a>
</form>