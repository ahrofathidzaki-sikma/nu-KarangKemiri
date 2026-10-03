@extends('layouts.admin')
@section('title', $pengurus->exists ? 'Edit Pengurus' : 'Tambah Pengurus')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $pengurus->exists ? route('admin.anggota.update', $pengurus) : route('admin.anggota.store') }}" class="admin-card p-3 p-lg-4" style="max-width:720px">
  @csrf @if ($pengurus->exists) @method('PUT') @endif
  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Nama *</label>
      <input name="nama" value="{{ old('nama', $pengurus->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Jabatan *</label>
      <input name="jabatan" value="{{ old('jabatan', $pengurus->jabatan) }}" class="form-control @error('jabatan') is-invalid @enderror" required placeholder="Contoh: Ketua IPNU">@error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6">
      <label class="form-label">Organisasi *</label>
      <select name="organisasi" class="form-select @error('organisasi') is-invalid @enderror" required>
        <option value="ipnu" @selected(old('organisasi', $pengurus->organisasi ?? 'ipnu') === 'ipnu')>IPNU (Laki-laki)</option>
        <option value="ippnu" @selected(old('organisasi', $pengurus->organisasi ?? '') === 'ippnu')>IPPNU (Perempuan)</option>
      </select>
      @error('organisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6"><label class="form-label">Periode</label>
      <input name="periode" value="{{ old('periode', $pengurus->periode) }}" class="form-control @error('periode') is-invalid @enderror" placeholder="Contoh: 2026-2030">@error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Nomor WA <span class="text-muted fw-normal">(opsional)</span></label>
      <input name="nomor_wa" value="{{ old('nomor_wa', $pengurus->nomor_wa) }}" class="form-control @error('nomor_wa') is-invalid @enderror" placeholder="08xxxxxxxxxx">@error('nomor_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  </div>
  <div class="mb-3"><label class="form-label">Keterangan</label>
    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pengurus->keterangan) }}</textarea>@error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-4"><label class="form-label">Foto (maks 2 MB)</label>
    @if ($pengurus->foto_url)<div class="mb-2"><img src="{{ $pengurus->foto_url }}" style="height:90px" class="rounded"> <label class="ms-2 small"><input type="checkbox" name="hapus_foto" value="1"> hapus foto</label></div>@endif
    <input type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">@error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <button class="btn btn-nu">Simpan</button> <a href="{{ route('admin.anggota.index') }}" class="btn btn-light">Batal</a>
</form>
@endsection
