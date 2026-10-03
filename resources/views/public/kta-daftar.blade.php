@extends('layouts.public')
@section('title', 'Daftar Anggota — KTA')
@section('content')
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Keanggotaan</div>
    <h1>Formulir Pendaftaran Anggota</h1>
    <p class="lead text-white-50 mb-0">Lengkapi data di bawah untuk membuat Kartu Tanda Anggota (KTA).</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:720px">
    <form method="POST" action="{{ route('kta.daftar.store') }}" enctype="multipart/form-data" class="card-nu p-4">
      @csrf

      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Nama Lengkap *</label>
          <input name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required>
          @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Jenis Kelamin *</label>
          <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
            <option value="">Pilih</option>
            <option value="L" @selected(old('jenis_kelamin')==='L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin')==='P')>Perempuan</option>
          </select>
          @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">NIK <span class="text-muted fw-normal">(opsional)</span></label>
          <input name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" maxlength="20" placeholder="16 digit">
          @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Nomor WA</label>
          <input name="no_wa" value="{{ old('no_wa') }}" class="form-control @error('no_wa') is-invalid @enderror" placeholder="08xxxxxxxxxx">
          @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Tempat Lahir</label>
          <input name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" rows="2" class="form-control">{{ old('alamat') }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Organisasi *</label>
          <select name="organisasi" class="form-select @error('organisasi') is-invalid @enderror" required>
            <option value="nu" @selected(old('organisasi', 'nu')==='nu')>NU (umum)</option>
            <option value="ipnu" @selected(old('organisasi')==='ipnu')>IPNU (laki-laki)</option>
            <option value="ippnu" @selected(old('organisasi')==='ippnu')>IPPNU (perempuan)</option>
          </select>
          @error('organisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Foto (opsional, maks 2 MB)</label>
          <input type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
          @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
          <label class="form-label">Keterangan</label>
          <textarea name="keterangan" rows="2" class="form-control" placeholder="Opsional">{{ old('keterangan') }}</textarea>
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-nu"><i class="bi bi-check2-circle me-1"></i> Daftar &amp; Buat KTA</button>
        <a href="{{ route('kta.index') }}" class="btn btn-light">Batal</a>
      </div>
    </form>
  </div>
</section>
@endsection
