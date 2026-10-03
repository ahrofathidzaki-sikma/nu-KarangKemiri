@extends('layouts.admin')
@section('title', $anggota->exists ? 'Edit Anggota' : 'Tambah Anggota')
@section('content')
<form method="POST" enctype="multipart/form-data"
  action="{{ $anggota->exists ? route('admin.kta.update', $anggota) : route('admin.kta.store') }}"
  class="admin-card p-3 p-lg-4" style="max-width:800px">
  @csrf @if ($anggota->exists) @method('PUT') @endif

  @if ($anggota->exists)
    <div class="alert alert-light border mb-3 py-2">
      <strong>No. Anggota:</strong> <span class="font-monospace">{{ $anggota->nomor_anggota }}</span>
    </div>
  @endif

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      <label class="form-label">Nama Lengkap *</label>
      <input name="nama" value="{{ old('nama', $anggota->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">NIK <span class="text-muted fw-normal">(opsional)</span></label>
      <input name="nik" value="{{ old('nik', $anggota->nik) }}" class="form-control @error('nik') is-invalid @enderror" maxlength="20">
      @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Jenis Kelamin *</label>
      <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
        <option value="L" @selected(old('jenis_kelamin', $anggota->jenis_kelamin) === 'L')>Laki-laki</option>
        <option value="P" @selected(old('jenis_kelamin', $anggota->jenis_kelamin) === 'P')>Perempuan</option>
      </select>
      @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Tempat Lahir</label>
      <input name="tempat_lahir" value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" class="form-control @error('tempat_lahir') is-invalid @enderror">
      @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}" class="form-control @error('tanggal_lahir') is-invalid @enderror">
      @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $anggota->alamat) }}</textarea>
      @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Nomor WA</label>
      <input name="no_wa" value="{{ old('no_wa', $anggota->no_wa) }}" class="form-control @error('no_wa') is-invalid @enderror" placeholder="08xxxxxxxxxx">
      @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Organisasi *</label>
      <select name="organisasi" class="form-select @error('organisasi') is-invalid @enderror" required>
        <option value="nu" @selected(old('organisasi', $anggota->organisasi ?? 'nu') === 'nu')>NU (umum)</option>
        <option value="ipnu" @selected(old('organisasi', $anggota->organisasi) === 'ipnu')>IPNU (laki-laki)</option>
        <option value="ippnu" @selected(old('organisasi', $anggota->organisasi) === 'ippnu')>IPPNU (perempuan)</option>
      </select>
      @error('organisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Status *</label>
      <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="aktif" @selected(old('status', $anggota->status ?? 'aktif') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(old('status', $anggota->status) === 'nonaktif')>Nonaktif / Undur</option>
      </select>
      @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Tanggal Bergabung</label>
      <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $anggota->tanggal_bergabung?->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-4 undur-fields">
      <label class="form-label">Tanggal Undur</label>
      <input type="date" name="tanggal_undur" value="{{ old('tanggal_undur', $anggota->tanggal_undur?->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-4 undur-fields">
      <label class="form-label">Alasan Undur</label>
      <input name="alasan_undur" value="{{ old('alasan_undur', $anggota->alasan_undur) }}" class="form-control" placeholder="Contoh: Pindah domisili">
    </div>
    <div class="col-12">
      <label class="form-label">Keterangan</label>
      <textarea name="keterangan" rows="2" class="form-control">{{ old('keterangan', $anggota->keterangan) }}</textarea>
    </div>
    <div class="col-12">
      <label class="form-label">Foto (maks 2 MB)</label>
      @if ($anggota->foto_url)
        <div class="mb-2">
          <img src="{{ $anggota->foto_url }}" style="height:90px" class="rounded">
          <label class="ms-2 small"><input type="checkbox" name="hapus_foto" value="1"> hapus foto</label>
        </div>
      @endif
      <input type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
      @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <button class="btn btn-nu">Simpan</button>
  <a href="{{ route('admin.kta.index') }}" class="btn btn-light">Batal</a>
</form>

<script>
(function () {
  const sel = document.getElementById('status');
  const fields = document.querySelectorAll('.undur-fields');
  function toggle() {
    const show = sel.value === 'nonaktif';
    fields.forEach(el => el.style.display = show ? '' : 'none');
  }
  sel.addEventListener('change', toggle);
  toggle();
})();
</script>
@endsection
