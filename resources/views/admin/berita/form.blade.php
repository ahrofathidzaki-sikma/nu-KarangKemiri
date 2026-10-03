@extends('layouts.admin')
@section('title', $berita->exists ? 'Edit Berita' : 'Tambah Berita')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $berita->exists ? route('admin.berita.update', $berita) : route('admin.berita.store') }}" class="admin-card p-3 p-lg-4" style="max-width:820px">
  @csrf @if ($berita->exists) @method('PUT') @endif
  <div class="mb-3"><label class="form-label">Judul *</label>
    <input name="judul" value="{{ old('judul', $berita->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>@error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Tanggal *</label>
      <input type="date" name="tanggal" value="{{ old('tanggal', optional($berita->tanggal)->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>@error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Penulis *</label>
      <input name="penulis" value="{{ old('penulis', $berita->penulis) }}" class="form-control @error('penulis') is-invalid @enderror" required>@error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  </div>
  <div class="mb-3"><label class="form-label">Gambar (JPG/PNG/WEBP, maks 2 MB)</label>
    @if ($berita->gambar_url)<div class="mb-2"><img src="{{ $berita->gambar_url }}" style="max-height:120px" class="rounded"> <label class="ms-2 small"><input type="checkbox" name="hapus_gambar" value="1"> hapus gambar</label></div>@endif
    <input type="file" name="gambar" accept="image/*" class="form-control @error('gambar') is-invalid @enderror">@error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-4"><label class="form-label">Isi Berita *</label>
    <textarea name="isi" rows="10" class="form-control @error('isi') is-invalid @enderror" required>{{ old('isi', $berita->isi) }}</textarea>@error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <button class="btn btn-nu">Simpan</button> <a href="{{ route('admin.berita.index') }}" class="btn btn-light">Batal</a>
</form>
@endsection
