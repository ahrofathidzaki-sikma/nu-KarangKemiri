@extends('layouts.admin')
@section('title', $dokumentasi->exists ? 'Edit Dokumentasi' : 'Tambah Dokumentasi')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $dokumentasi->exists ? route('admin.dokumentasi.update', $dokumentasi) : route('admin.dokumentasi.store') }}" class="admin-card p-3 p-lg-4" style="max-width:720px">
  @csrf @if ($dokumentasi->exists) @method('PUT') @endif
  <div class="mb-3"><label class="form-label">Agenda Terkait</label>
    <select name="agenda_id" class="form-select @error('agenda_id') is-invalid @enderror">
      <option value="">— Dokumentasi umum (tanpa agenda) —</option>
      @foreach ($agendas as $a)<option value="{{ $a->id }}" @selected(old('agenda_id', $dokumentasi->agenda_id) == $a->id)>{{ $a->judul }} ({{ $a->tanggal->format('d/m/Y') }})</option>@endforeach
    </select>@error('agenda_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-3"><label class="form-label">Judul *</label>
    <input name="judul" value="{{ old('judul', $dokumentasi->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>@error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-3"><label class="form-label">Keterangan</label>
    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $dokumentasi->keterangan) }}</textarea>@error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-4"><label class="form-label">Gambar {{ $dokumentasi->exists ? '(kosongkan jika tidak diganti)' : '*' }} — maks 4 MB</label>
    @if ($dokumentasi->gambar_url)<div class="mb-2"><img src="{{ $dokumentasi->gambar_url }}" style="height:110px" class="rounded"></div>@endif
    <input type="file" name="gambar" accept="image/*" class="form-control @error('gambar') is-invalid @enderror">@error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <button class="btn btn-nu">Simpan</button> <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-light">Batal</a>
</form>
@endsection
