<div class="form-group">
    <label for="{{ $prefix }}judul">Judul</label>
    <input type="text" class="form-control" name="judul" id="{{ $prefix }}judul" required placeholder="Masukkan Judul Skripsi" value="{{ old('judul', $skripsi->judul ?? '') }}">
</div>
<div class="form-group">
    <label for="{{ $prefix }}abstrak">Abstrak</label>
    <textarea class="form-control" name="abstrak" id="{{ $prefix }}abstrak" required placeholder="Masukkan Abstrak">{{ old('abstrak', $skripsi->abstrak ?? '') }}</textarea>
</div>
<div class="d-flex" style="margin: -7px">
    <div class="form-group col-md-3">
        <label for="penulis">Penulis</label>
        <input type="text" class="form-control" name="penulis" id="penulis" value="{{ Auth::user()->name }}" readonly>
    </div>
    <div class="form-group col-md-3">
        <label for="{{ $prefix }}dospem">Dosen Pembimbing</label>
        <select name="dospem" class="form-control" id="{{ $prefix }}dospem" required>
            <option value="">Pilih</option>
            @foreach ($namaDospem as $dosen)
                <option value="{{ $dosen->nama }}" {{ (old('dospem', $skripsi->dospem ?? '') == $dosen->nama) ? 'selected' : '' }}>{{ $dosen->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="{{ $prefix }}rilis">Rilis Pada Tahun</label>
        <input type="text" class="form-control" name="rilis" id="{{ $prefix }}rilis" required placeholder="Harus 4 Angka" value="{{ old('rilis', $skripsi->rilis ?? '') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="{{ $prefix }}halaman">Halaman</label>
        <input type="text" class="form-control" name="halaman" id="{{ $prefix }}halaman" required placeholder="Masukkan Jumlah Halaman" value="{{ old('halaman', $skripsi->halaman ?? '') }}">
    </div>
</div>
<div class="form-group">
    <label for="{{ $prefix }}file_skripsi">Pilih File Skripsi (PDF, Maks. 10 MB):</label>
    <input type="file" class="form-control" name="file_skripsi" id="{{ $prefix }}file_skripsi" accept=".pdf" {{ isset($prefix) && $prefix == 'edit-' ? '' : 'required' }}>
    @if(isset($prefix) && $prefix == 'edit-' && !empty($skripsi->file_skripsi))
        <small class="form-text text-success">File saat ini: {{ $skripsi->file_skripsi }}</small>
    @endif
</div>
