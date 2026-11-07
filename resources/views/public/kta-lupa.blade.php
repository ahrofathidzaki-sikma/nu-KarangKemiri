@extends('layouts.public')
@section('title', 'Lupa Nomor Anggota — KTA')
@section('content')
<link href="{{ asset('css/kta.css') }}" rel="stylesheet">
<header class="page-head">
  <div class="container">
    <a href="{{ route('kta.index') }}" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali ke KTA</a>
    <h1 class="mt-2">Lupa Nomor Anggota?</h1>
    <p class="lead text-white-50 mb-0">Tidak masalah. Kami bantu temukan nomor Anda.</p>
  </div>
</header>

<section class="section kta-page">
  <div class="container" style="max-width:760px">
    @include('partials.kta-alert')

    <form method="POST" action="{{ route('kta.lupa.cari') }}" class="opsi-kta utama mb-4" novalidate>
      @csrf
      <div class="mb-3">
        <label class="form-label" for="nama">Nama lengkap Anda <span class="text-danger">*</span></label>
        <input id="nama" name="nama" value="{{ old('nama') }}" autocomplete="name" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Ahmad Fulan">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Gelar atau urutan kata tidak harus persis sama.</div>
      </div>

      <div class="mb-3">@include('partials.tanggal-lahir', ['label' => 'Tanggal lahir', 'wajib' => false])</div>

      <div class="text-center text-muted small my-3">— atau, jika tidak ingat tanggal lahir yang didaftarkan —</div>

      <div class="mb-4">
        <label class="form-label" for="no_wa">Nomor WhatsApp yang dulu didaftarkan</label>
        <input id="no_wa" name="no_wa" type="tel" inputmode="tel" value="{{ old('no_wa') }}" class="form-control @error('no_wa') is-invalid @enderror" placeholder="0812 3456 7890">
        @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn btn-nu w-100"><i class="bi bi-search"></i> Cari nomor anggota saya</button>
    </form>

    <div class="catatan mb-4"><i class="bi bi-shield-check"></i> Demi keamanan, nomor anggota tidak ditampilkan hanya berdasarkan nama. Isi <strong>tanggal lahir</strong> atau <strong>nomor WA</strong> sebagai pembuktian.</div>

    @include('partials.kta-kontak')
  </div>
</section>
@endsection
