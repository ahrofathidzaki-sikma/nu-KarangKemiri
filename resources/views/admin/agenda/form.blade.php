@extends('layouts.admin')
@section('title', $agenda->exists ? 'Edit Agenda' : 'Tambah Agenda')
@section('content')
<form method="POST" action="{{ $agenda->exists ? route('admin.agendas.update', $agenda) : route('admin.agendas.store') }}" class="admin-card p-3 p-lg-4" style="max-width:820px">
  @csrf @if ($agenda->exists) @method('PUT') @endif
  <div class="mb-3"><label class="form-label">Judul Kegiatan *</label>
    <input name="judul" value="{{ old('judul', $agenda->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>@error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="row g-3 mb-2">
    <div class="col-md-6"><label class="form-label">Tanggal *</label>
      <input type="date" name="tanggal" value="{{ old('tanggal', optional($agenda->tanggal)->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>@error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Waktu Mulai</label>
      <input type="time" name="waktu" value="{{ old('waktu', $agenda->waktu) }}" class="form-control @error('waktu') is-invalid @enderror">@error('waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  </div>
  <div class="alert alert-success py-2 small mb-3"><i class="bi bi-magic"></i> Status diatur <strong>otomatis</strong>: <em>Akan Datang</em> sebelum waktu mulai, lalu pindah ke <em>Selesai</em> (Kegiatan yang Sudah Berlalu) saat kegiatan dimulai.
    @if ($agenda->exists) Status saat ini: <strong>{{ $agenda->status }}</strong>.@endif</div>
  <div class="mb-3"><label class="form-label">Lokasi *</label>
    <input name="lokasi" value="{{ old('lokasi', $agenda->lokasi) }}" class="form-control @error('lokasi') is-invalid @enderror" required>@error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-4"><label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $agenda->deskripsi) }}</textarea></div>
  <button class="btn btn-nu">Simpan</button> <a href="{{ route('admin.agendas.index') }}" class="btn btn-light">Batal</a>
</form>
@endsection
