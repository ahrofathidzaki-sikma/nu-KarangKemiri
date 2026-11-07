@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
<section class="hero">
  <div class="container py-5">
    <div class="row align-items-center g-5 py-lg-4">
      <div class="col-lg-6">
        <span class="hero-kicker mb-3"><i class="bi bi-patch-check-fill"></i> Website resmi {{ $pengaturan->nama_organisasi }}</span>
        <h1 class="mb-3">Bersama menjaga <em>tradisi</em> dan merawat umat di {{ $pengaturan->nama_desa }}</h1>
        <p class="lead mb-4">{{ $pengaturan->deskripsi }}</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('agenda.index') }}" class="btn btn-mint">Lihat Agenda</a>
          <a href="{{ route('kta.index') }}" class="btn btn-outline-light">Kartu Anggota (KTA)</a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-card mb-3">
          <div class="eyebrow mb-2">Kegiatan terdekat</div>
          @forelse ($agendas->take(2) as $a)
            <a href="{{ route('agenda.show', $a) }}" class="d-flex gap-3 text-decoration-none text-reset py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="date-chip"><b>{{ $a->tanggal->format('d') }}</b><small>{{ $a->tanggal->translatedFormat('M') }}</small></div>
              <div><div class="fw-bold">{{ $a->judul }}</div><div class="meta"><i class="bi bi-geo-alt"></i> {{ $a->lokasi }}</div></div>
            </a>
          @empty
            <p class="meta mb-0">Belum ada agenda mendatang.</p>
          @endforelse
        </div>
        <div class="row g-2">
          <div class="col-4"><div class="stat-tile"><b>{{ $jumlah['agenda'] }}</b><span>Agenda</span></div></div>
          <div class="col-4"><div class="stat-tile"><b>{{ $jumlah['berita'] }}</b><span>Berita</span></div></div>
          <div class="col-4"><div class="stat-tile"><b>{{ $jumlah['pengurus'] }}</b><span>Pengurus</span></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Pintasan --}}
<section class="section py-4 border-bottom">
  <div class="container">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <a href="{{ route('struktur') }}" class="card-nu p-3 text-decoration-none text-reset d-block h-100 text-center quick-link">
          <div class="text-success mb-2"><i class="bi bi-diagram-3 fs-3"></i></div>
          <div class="fw-semibold">Struktur</div>
          <div class="meta small">Pengurus IPNU &amp; IPPNU</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('kta.index') }}" class="card-nu p-3 text-decoration-none text-reset d-block h-100 text-center quick-link">
          <div class="text-success mb-2"><i class="bi bi-person-vcard fs-3"></i></div>
          <div class="fw-semibold">KTA</div>
          <div class="meta small">Daftar &amp; cetak kartu</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('dokumentasi') }}" class="card-nu p-3 text-decoration-none text-reset d-block h-100 text-center quick-link">
          <div class="text-success mb-2"><i class="bi bi-images fs-3"></i></div>
          <div class="fw-semibold">Galeri</div>
          <div class="meta small">Dokumentasi kegiatan</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('tentang') }}" class="card-nu p-3 text-decoration-none text-reset d-block h-100 text-center quick-link">
          <div class="text-success mb-2"><i class="bi bi-info-circle fs-3"></i></div>
          <div class="fw-semibold">Tentang</div>
          <div class="meta small">Profil organisasi</div>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div><div class="eyebrow">Agenda</div><h2 class="section-title mb-0">Kegiatan yang akan datang</h2></div>
      <a href="{{ route('agenda.index') }}" class="fw-semibold text-decoration-none d-none d-sm-block">Semua agenda <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
      @forelse ($agendas as $a)<div class="col-md-6 col-lg-4">@include('partials.agenda-card', ['a' => $a])</div>
      @empty<div class="col-12"><div class="alert alert-light border">Belum ada kegiatan yang akan datang.</div></div>@endforelse
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div><div class="eyebrow">Artikel</div><h2 class="section-title mb-0">Berita terbaru</h2></div>
      <a href="{{ route('berita.index') }}" class="fw-semibold text-decoration-none d-none d-sm-block">Semua berita <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-4">
      @forelse ($beritas as $b)<div class="col-md-6 col-lg-4">@include('partials.berita-card', ['b' => $b])</div>
      @empty<div class="col-12"><div class="alert alert-light border">Belum ada berita.</div></div>@endforelse
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div><div class="eyebrow">Galeri</div><h2 class="section-title mb-0">Dokumentasi terbaru</h2></div>
      <a href="{{ route('dokumentasi') }}" class="fw-semibold text-decoration-none d-none d-sm-block">Semua foto <i class="bi bi-arrow-right"></i></a>
    </div>
    @include('partials.gallery', ['items' => $dokumentasis])
  </div>
</section>
@endsection
