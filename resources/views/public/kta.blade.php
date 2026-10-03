@extends('layouts.public')
@section('title', 'KTA — Kartu Tanda Anggota')
@section('content')
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Keanggotaan</div>
    <h1>Kartu Tanda Anggota (KTA)</h1>
    <p class="lead text-white-50 mb-0">Daftar sebagai anggota NU desa atau cek &amp; cetak KTA Anda.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="row g-4 justify-content-center">

      {{-- Daftar --}}
      <div class="col-md-6 col-lg-5">
        <div class="card-nu p-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px">
              <i class="bi bi-person-plus fs-4"></i>
            </div>
            <div>
              <h2 class="h5 mb-0">Daftar Anggota</h2>
              <p class="meta mb-0">Buat KTA baru</p>
            </div>
          </div>
          <p class="small text-muted">Isi formulir pendaftaran untuk menjadi anggota NU di desa ini. Setelah berhasil, Anda akan mendapat nomor anggota dan dapat mencetak KTA.</p>
          <a href="{{ route('kta.daftar') }}" class="btn btn-nu w-100 mt-2"><i class="bi bi-pencil-square me-1"></i> Formulir Pendaftaran</a>
        </div>
      </div>

      {{-- Cek KTA --}}
      <div class="col-md-6 col-lg-5">
        <div class="card-nu p-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px">
              <i class="bi bi-search fs-4"></i>
            </div>
            <div>
              <h2 class="h5 mb-0">Cek / Cetak KTA</h2>
              <p class="meta mb-0">Sudah punya nomor anggota?</p>
            </div>
          </div>
          <form method="POST" action="{{ route('kta.cek') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Nomor Anggota atau NIK</label>
              <input type="text" name="kunci" value="{{ old('kunci') }}" class="form-control @error('kunci') is-invalid @enderror" placeholder="Contoh: NU-2026-0001" required>
              @error('kunci')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-outline-success w-100"><i class="bi bi-card-heading me-1"></i> Cek KTA</button>
          </form>
        </div>
      </div>

    </div>

    <div class="mt-5 text-center">
      <p class="meta mb-0">KTA digunakan sebagai identitas keanggotaan NU di {{ $pengaturan->nama_desa }}. Untuk pengunduran diri atau perubahan data, hubungi pengurus atau admin.</p>
    </div>
  </div>
</section>
@endsection
