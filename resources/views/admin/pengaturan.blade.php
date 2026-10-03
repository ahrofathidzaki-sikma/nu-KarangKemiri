@extends('layouts.admin')
@section('title', 'Pengaturan Website')
@section('content')
@php $f = fn($k) => old($k, $p->$k); @endphp
<form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data" class="admin-card p-3 p-lg-4" style="max-width:860px">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Nama Organisasi *</label><input name="nama_organisasi" value="{{ $f('nama_organisasi') }}" class="form-control @error('nama_organisasi') is-invalid @enderror" required>@error('nama_organisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Desa &amp; Kabupaten *</label><input name="nama_desa" value="{{ $f('nama_desa') }}" placeholder="Desa Karangkemiri, Kab. Banyumas" class="form-control @error('nama_desa') is-invalid @enderror" required>@error('nama_desa')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Logo (maks 2 MB)</label>
      @if ($p->logo_url)<div class="mb-1"><img src="{{ $p->logo_url }}" style="height:48px" class="rounded"></div>@endif
      <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">@error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" rows="3" class="form-control">{{ $f('deskripsi') }}</textarea></div>
    <div class="col-12"><label class="form-label">Alamat</label><input name="alamat" value="{{ $f('alamat') }}" class="form-control @error('alamat') is-invalid @enderror"></div>
    <div class="col-md-6"><label class="form-label">Telepon</label><input name="telepon" value="{{ $f('telepon') }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ $f('email') }}" class="form-control @error('email') is-invalid @enderror">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    @foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube'] as $k => $l)
      <div class="col-md-4"><label class="form-label">{{ $l }} (URL)</label><input name="{{ $k }}" value="{{ $f($k) }}" placeholder="https://..." class="form-control @error($k) is-invalid @enderror">@error($k)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    @endforeach
  </div>
  <button class="btn btn-nu mt-4">Simpan Pengaturan</button>
</form>
@endsection
