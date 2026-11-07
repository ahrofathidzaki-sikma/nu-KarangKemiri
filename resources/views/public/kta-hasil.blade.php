@extends('layouts.public')
@section('title', 'KTA — ' . $anggota->nama)
@section('content')
<link href="{{ asset('css/kta.css') }}" rel="stylesheet">
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Keanggotaan</div>
    <h1>KTA Anda</h1>
  </div>
</header>

<section class="section kta-page">
  <div class="container" style="max-width:680px">
    @include('partials.kta-alert')

    {{-- Kartu digital: nomor dibuat besar agar mudah dibaca & difoto --}}
    <div class="kartu-digital mb-3">
      <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
          <div class="label">Kartu Tanda Anggota</div>
          <div class="fw-bold">{{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}</div>
        </div>
        <span class="badge rounded-pill {{ $anggota->status === 'aktif' ? 'text-bg-light' : 'text-bg-secondary' }}">
          {{ $anggota->status === 'aktif' ? 'Aktif' : 'Nonaktif / Undur' }}
        </span>
      </div>
      <div class="label mb-1">Nomor anggota</div>
      <div class="nomor-besar mb-3" id="nomor">{{ $anggota->nomor_anggota }}</div>
      <div class="d-flex gap-3 align-items-center">
        @if ($anggota->foto_url)
          <img src="{{ $anggota->foto_url }}" alt="{{ $anggota->nama }}" class="rounded-3" style="width:64px;height:80px;object-fit:cover">
        @else
          <div class="avatar m-0" style="width:64px;height:80px;border-radius:12px;font-size:1.6rem">{{ strtoupper(substr($anggota->nama, 0, 1)) }}</div>
        @endif
        <div>
          <div class="fs-5 fw-bold">{{ $anggota->nama }}</div>
          <span class="badge text-bg-light">{{ $anggota->organisasi_label }}</span>
        </div>
      </div>
    </div>

    {{-- Aksi utama --}}
    <div class="kta-aksi d-grid gap-2 mb-4">
      @if ($anggota->status === 'aktif')
        <a href="{{ route('kta.cetak', $anggota->nomor_anggota) }}" class="btn btn-nu" target="_blank"><i class="bi bi-printer"></i> Cetak / simpan KTA (PDF)</a>
      @endif
      <button type="button" class="btn btn-outline-success" data-salin="{{ $anggota->nomor_anggota }}"><i class="bi bi-clipboard"></i> Salin nomor anggota</button>
      @if ($anggota->simpan_wa_link)
        <a href="{{ $anggota->simpan_wa_link }}" class="btn btn-outline-success" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> Kirim nomor ke WhatsApp saya</a>
      @endif
    </div>

    <div class="catatan mb-4">
      <strong><i class="bi bi-bookmark-heart"></i> Supaya tidak lupa lagi:</strong>
      <ul class="mb-0 mt-2 ps-3">
        <li>Potret layar ini, atau tulis nomor di buku catatan.</li>
        <li>Cetak KTA dan simpan di dompet.</li>
        <li>Lain waktu, cukup ingat <strong>nama</strong> dan <strong>tanggal lahir</strong> Anda lewat menu "Lupa nomor".</li>
      </ul>
    </div>

    <div class="card-nu p-4 mb-4">
      <h2 class="h6 mb-3">Data keanggotaan</h2>
      <dl class="row small mb-0">
        <dt class="col-sm-4 text-muted">Jenis kelamin</dt><dd class="col-sm-8">{{ $anggota->jenis_kelamin_label }}</dd>
        @if ($anggota->tempat_lahir || $anggota->tanggal_lahir)
          <dt class="col-sm-4 text-muted">Tempat, tgl lahir</dt>
          <dd class="col-sm-8">{{ $anggota->tempat_lahir ?: '-' }}{{ $anggota->tanggal_lahir ? ', '.$anggota->tanggal_lahir->translatedFormat('d F Y') : '' }}</dd>
        @endif
        @if ($anggota->alamat)<dt class="col-sm-4 text-muted">Alamat</dt><dd class="col-sm-8">{{ $anggota->alamat }}</dd>@endif
        @if ($anggota->no_wa)<dt class="col-sm-4 text-muted">No. WA</dt><dd class="col-sm-8">{{ $anggota->no_wa }}</dd>@endif
        @if ($anggota->tanggal_bergabung)<dt class="col-sm-4 text-muted">Bergabung</dt><dd class="col-sm-8">{{ $anggota->tanggal_bergabung->translatedFormat('d F Y') }}</dd>@endif
        @if ($anggota->status === 'nonaktif')
          <dt class="col-sm-4 text-muted">Tanggal undur</dt><dd class="col-sm-8">{{ $anggota->tanggal_undur?->translatedFormat('d F Y') ?: '-' }}</dd>
          @if ($anggota->alasan_undur)<dt class="col-sm-4 text-muted">Alasan</dt><dd class="col-sm-8">{{ $anggota->alasan_undur }}</dd>@endif
        @endif
      </dl>
      <p class="meta mt-3 mb-0">Ada data yang salah atau ingin berhenti menjadi anggota? Hubungi pengurus.</p>
    </div>

    @if ($anggota->status === 'nonaktif')@include('partials.kta-kontak')@endif

    <a href="{{ route('kta.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali ke KTA</a>
  </div>
</section>

<script>
  document.querySelectorAll('[data-salin]').forEach(function (b) {
    b.addEventListener('click', function () {
      var teks = b.getAttribute('data-salin'), asli = b.innerHTML;
      function selesai() { b.innerHTML = '<i class="bi bi-check2"></i> Tersalin'; setTimeout(function () { b.innerHTML = asli; }, 1800); }
      if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(teks).then(selesai); return; }
      var i = document.createElement('input'); i.value = teks; document.body.appendChild(i); i.select();
      try { document.execCommand('copy'); } catch (e) {} i.remove(); selesai();
    });
  });
</script>
@endsection
