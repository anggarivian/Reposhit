@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Mahasiswa Skripsi</h1>
@stop

@section('content')
    <div class="container-fluid my-4">

        @if(is_null($skripsi))
            {{-- --------------------------------------------------------
                FORM: tampilkan hanya jika user belum punya skripsi
                -------------------------------------------------------- --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-plus mr-2"></i>Tambah Data Skripsi</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tambah.skripsi') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Judul & Abstrak --}}
                        <div class="form-group">
                            <label for="title">Judul <span class="text-danger">*</span></label>
                                <div class="alert alert-info py-1 px-2 mb-2" style="font-size: 13px;">
                                    <i class="fas fa-info-circle mr-1"></i> Huruf pertama pada judul <strong>wajib huruf kapital</strong>.
                                </div>
                            <input type="text"
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Abstrak <span class="text-danger">*</span></label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3"
                                required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Dosen Pembimbing --}}
                        <div class="form-group">
                            <label for="contributor">Dosen Pembimbing <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ Auth::user()->dosen->nama }}" readonly>
                        </div>
                        {{-- Tahun Rilis & Jumlah Halaman --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="date_issued">Tahun Rilis <span class="text-danger">*</span></label>
                                <input type="number"
                                    name="date_issued"
                                    id="date_issued"
                                    class="form-control @error('date_issued') is-invalid @enderror"
                                    value="{{ old('date_issued') }}"
                                    min="1900"
                                    max="{{ date('Y') }}"
                                    required>
                                @error('date_issued')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="coverage">Jumlah Halaman <span class="text-danger">*</span></label>
                                <input type="number"
                                    name="coverage"
                                    id="coverage"
                                    class="form-control @error('coverage') is-invalid @enderror"
                                    value="{{ old('coverage') }}"
                                    min="1"
                                    required>
                                @error('coverage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Kata Kunci --}}
                        <div class="form-group">
                            <label for="keywords">Kata Kunci <span class="text-danger">*</span></label>
                            <input type="text"
                                name="keywords"
                                id="keywords"
                                class="form-control @error('keywords') is-invalid @enderror"
                                value="{{ old('keywords') }}"
                                required>
                            <small class="form-text text-muted">Pisahkan dengan koma.</small>
                            @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Upload File --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="file_skripsi">File Skripsi <span class="text-danger">*</span></label>
                                <input type="file"
                                    name="file_skripsi"
                                    id="file_skripsi"
                                    class="form-control-file @error('file_skripsi') is-invalid @enderror"
                                    accept=".pdf"
                                    required>
                                @error('file_skripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="file_dapus">File Daftar Pustaka <span class="text-danger">*</span></label>
                                <input type="file"
                                    name="file_dapus"
                                    id="file_dapus"
                                    class="form-control-file @error('file_dapus') is-invalid @enderror"
                                    accept=".pdf"
                                    required>
                                @error('file_dapus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="file_abstrak">File Abstrak <span class="text-danger">*</span></label>
                                <input type="file"
                                    name="file_abstrak"
                                    id="file_abstrak"
                                    class="form-control-file @error('file_abstrak') is-invalid @enderror"
                                    accept=".pdf"
                                    required>
                                @error('file_abstrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Hidden defaults --}}
                        <input type="hidden" name="publisher" value="Universitas Suryakancana Fakultas Sains Terapan">
                        <input type="hidden" name="language"  value="id">
                        <input type="hidden" name="type"      value="Skripsi">
                        <input type="hidden" name="format"    value="PDF">

                        <div class="text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Submit Skripsi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @else
            {{-- --------------------------------------------------------
                DETAIL & STATUS: tampilkan jika user sudah punya skripsi
                -------------------------------------------------------- --}}

            {{-- Cards 2–4: Status Berdasarkan $skripsi->status --}}
            @switch($skripsi->status)

                {{-- Status 0: Belum Diverifikasi --}}
                @case(0)
                {{-- Card 1: Detail Skripsi --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Detail Skripsi Anda</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Judul</dt>
                            <dd class="col-sm-9">{{ $skripsi->judul }}</dd>

                            <dt class="col-sm-3">Abstrak</dt>
                            <dd class="col-sm-9">{{ $skripsi->abstrak }}</dd>

                            <dt class="col-sm-3">Dosen Pembimbing</dt>
                            <dd class="col-sm-9">{{ $skripsi->dosen->nama ?? '-' }}</dd>

                            <dt class="col-sm-3">Tahun Rilis</dt>
                            <dd class="col-sm-9">{{ $skripsi->rilis }}</dd>

                            <dt class="col-sm-3">Halaman</dt>
                            <dd class="col-sm-9">{{ $skripsi->halaman }}</dd>

                            <dt class="col-sm-3">Kata Kunci</dt>
                            <dd class="col-sm-9">{{ $skripsi->katakunci }}</dd>

                            @if($skripsi->metadata)
                                <dt class="col-sm-3">Bidang Keilmuan</dt>
                                <dd class="col-sm-9">{{ $skripsi->metadata->subject }}</dd>
                            @endif

                            <dt class="col-sm-3">Unduh File</dt>
                            <dd class="col-sm-9">
                                <a href="{{ Storage::url('skripsi_files/'.$skripsi->file_skripsi) }}" target="_blank">
                                    {{ $skripsi->file_skripsi }}
                                </a>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-hourglass-start mr-2"></i>Menunggu Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        <p>Skripsi Anda belum diverifikasi oleh admin.</p>
                        <ul>
                            <li>Di-submit pada: {{ $skripsi->created_at->format('d M Y H:i') }}</li>
                            <li>Status: <strong>Belum Diverifikasi</strong></li>
                        </ul>
                    </div>
                </div>
                @break

                {{-- Status 1: Sudah Diverifikasi --}}
                @case(1)
                {{-- Card 1: Detail Skripsi --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Detail Skripsi Anda</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Judul</dt>
                            <dd class="col-sm-9">{{ $skripsi->judul }}</dd>

                            <dt class="col-sm-3">Abstrak</dt>
                            <dd class="col-sm-9">{{ $skripsi->abstrak }}</dd>

                            <dt class="col-sm-3">Dosen Pembimbing</dt>
                            <dd class="col-sm-9">{{ $skripsi->dosen->nama ?? '-' }}</dd>

                            <dt class="col-sm-3">Tahun Rilis</dt>
                            <dd class="col-sm-9">{{ $skripsi->rilis }}</dd>

                            <dt class="col-sm-3">Halaman</dt>
                            <dd class="col-sm-9">{{ $skripsi->halaman }}</dd>

                            <dt class="col-sm-3">Kata Kunci</dt>
                            <dd class="col-sm-9">{{ $skripsi->katakunci }}</dd>

                            @if($skripsi->metadata)
                                <dt class="col-sm-3">Bidang Keilmuan</dt>
                                <dd class="col-sm-9">{{ $skripsi->metadata->subject }}</dd>
                            @endif

                            <dt class="col-sm-3">Unduh File</dt>
                            <dd class="col-sm-9">
                                <a href="{{ Storage::url('skripsi_files/'.$skripsi->file_skripsi) }}" target="_blank">
                                    {{ $skripsi->file_skripsi }}
                                </a>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle mr-2"></i>Telah Diverifikasi</h5>
                    </div>
                    <div class="card-body">
                        <p>Skripsi Anda telah berhasil diverifikasi oleh admin.</p>
                        <ul>
                            <li>Di-verifikasi pada: {{ $skripsi->updated_at->format('d M Y H:i') }}</li>
                            <li>Status: <strong>Terverifikasi</strong></li>
                        </ul>
                    </div>
                </div>
                @break

                {{-- Status 2: Perlu Perbaikan --}}
                @case(2)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i>Perlu Perbaikan</h5>
                    </div>
                    <div class="card-body">
                        <p>Skripsi Anda memerlukan revisi/perbaikan sesuai masukan admin.</p>
                        <ul>
                            <li>Di-review pada: {{ $skripsi->updated_at->format('d M Y H:i') }}</li>
                            <li>Status: <strong>Perbaikan</strong></li>
                        </ul>
                        @if($notifikasi->deskripsi)
                            <div class="mt-3">
                                <h6>Catatan Admin:</h6>
                                <blockquote class="blockquote">
                                    {{ $notifikasi->deskripsi }}
                                </blockquote>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Form Perbaikan untuk Status 2 --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-edit mr-2"></i>Form Perbaikan Skripsi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ubah.skripsi', $skripsi->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Tampilkan error umum --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Judul & Abstrak --}}
                            <div class="form-group">
                                <label for="title">Judul <span class="text-danger">*</span></label>
                                <div class="alert alert-info py-1 px-2 mb-2" style="font-size: 13px;">
                                    <i class="fas fa-info-circle mr-1"></i> Huruf pertama pada judul <strong>wajib huruf kapital</strong>.
                                </div>
                                <input type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $skripsi->judul) }}"
                                    required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Abstrak <span class="text-danger">*</span></label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="3"
                                    required>{{ old('description', $skripsi->abstrak) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Dosen Pembimbing --}}
                            <div class="form-group">
                                <label for="contributor">Dosen Pembimbing <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->dosen->nama }}" readonly>
                                @error('contributor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tahun Rilis & Jumlah Halaman --}}
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="date_issued">Tahun Rilis <span class="text-danger">*</span></label>
                                    <input type="number"
                                        name="date_issued"
                                        id="date_issued"
                                        class="form-control @error('date_issued') is-invalid @enderror"
                                        value="{{ old('date_issued', $skripsi->rilis) }}"
                                        min="1900"
                                        max="{{ date('Y') }}"
                                        required>
                                    @error('date_issued')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="coverage">Jumlah Halaman <span class="text-danger">*</span></label>
                                    <input type="number"
                                        name="coverage"
                                        id="coverage"
                                        class="form-control @error('coverage') is-invalid @enderror"
                                        value="{{ old('coverage', $skripsi->halaman) }}"
                                        min="1"
                                        required>
                                    @error('coverage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Kata Kunci --}}
                            <div class="form-group">
                                <label for="keywords">Kata Kunci <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="keywords"
                                    id="keywords"
                                    class="form-control @error('keywords') is-invalid @enderror"
                                    value="{{ old('keywords', $skripsi->katakunci) }}"
                                    required>
                                <small class="form-text text-muted">Pisahkan dengan koma.</small>
                                @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Upload File (Optional) --}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="file_skripsi">File Skripsi</label>
                                    <div class="mb-2">
                                        <span>File saat ini: </span>
                                        <a href="{{ Storage::url('skripsi_files/'.$skripsi->file_skripsi) }}" target="_blank">
                                            {{ $skripsi->file_skripsi }}
                                        </a>
                                    </div>
                                    <input type="file"
                                        name="file_skripsi"
                                        id="file_skripsi"
                                        class="form-control-file @error('file_skripsi') is-invalid @enderror"
                                        accept=".pdf">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah</small>
                                    @error('file_skripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="file_dapus">File Daftar Pustaka</label>
                                    <div class="mb-2">
                                        <span>File saat ini: </span>
                                        <a href="{{ Storage::url('skripsi_files/'.$skripsi->file_dapus) }}" target="_blank">
                                            {{ $skripsi->file_dapus }}
                                        </a>
                                    </div>
                                    <input type="file"
                                        name="file_dapus"
                                        id="file_dapus"
                                        class="form-control-file @error('file_dapus') is-invalid @enderror"
                                        accept=".pdf">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah</small>
                                    @error('file_dapus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="file_abstrak">File Abstrak</label>
                                    <div class="mb-2">
                                        <span>File saat ini: </span>
                                        <a href="{{ Storage::url('skripsi_files/'.$skripsi->file_abstrak) }}" target="_blank">
                                            {{ $skripsi->file_abstrak }}
                                        </a>
                                    </div>
                                    <input type="file"
                                        name="file_abstrak"
                                        id="file_abstrak"
                                        class="form-control-file @error('file_abstrak') is-invalid @enderror"
                                        accept=".pdf">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah</small>
                                    @error('file_abstrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Hidden defaults --}}
                            <input type="hidden" name="publisher" value="Universitas Suryakancana Fakultas Sains Terapan">
                            <input type="hidden" name="language"  value="id">
                            <input type="hidden" name="type"      value="Skripsi">
                            <input type="hidden" name="format"    value="PDF">

                            <div class="text-right">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save mr-1"></i>Perbarui Skripsi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @break

                {{-- Default: Status Lainnya --}}
                @default
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Tidak Diketahui</h5>
                    </div>
                    <div class="card-body">
                        <p>Status skripsi Anda tidak dikenali. Silakan hubungi admin.</p>
                    </div>
                </div>
            @endswitch

        @endif

    </div>
@endsection
@section('js')
@stop
