@extends('layouts.public')
@section('title', 'KTA — ' . $anggota->nama)
@section('content')
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Keanggotaan</div>
    <h1>Data Anggota &amp; KTA</h1>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:640px">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <div class="card-nu p-4">
      <div class="d-flex gap-3 align-items-start mb-4">
        @if ($anggota->foto_url)
          <img src="{{ $anggota->foto_url }}" alt="{{ $anggota->nama }}" class="rounded" style="width:88px;height:110px;object-fit:cover">
        @else
          <div class="avatar" style="width:88px;height:110px;font-size:2rem;border-radius:8px">{{ strtoupper(substr($anggota->nama,0,1)) }}</div>
        @endif
        <div>
          <h2 class="h5 mb-1">{{ $anggota->nama }}</h2>
          <div class="font-monospace text-success fw-semibold">{{ $anggota->nomor_anggota }}</div>
          <div class="mt-2">
            @if ($anggota->status === 'aktif')
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif / Undur</span>
            @endif
            <span class="badge bg-light text-dark border">{{ $anggota->organisasi_label }}</span>
          </div>
        </div>
      </div>

      <dl class="row small mb-0">
        <dt class="col-sm-4 text-muted">Jenis Kelamin</dt>
        <dd class="col-sm-8">{{ $anggota->jenis_kelamin_label }}</dd>
        @if ($anggota->tempat_lahir || $anggota->tanggal_lahir)
          <dt class="col-sm-4 text-muted">TTL</dt>
          <dd class="col-sm-8">{{ $anggota->tempat_lahir ?: '-' }}{{ $anggota->tanggal_lahir ? ', '.$anggota->tanggal_lahir->format('d/m/Y') : '' }}</dd>
        @endif
        @if ($anggota->alamat)
          <dt class="col-sm-4 text-muted">Alamat</dt>
          <dd class="col-sm-8">{{ $anggota->alamat }}</dd>
        @endif
        @if ($anggota->no_wa)
          <dt class="col-sm-4 text-muted">No. WA</dt>
          <dd class="col-sm-8">{{ $anggota->no_wa }}</dd>
        @endif
        @if ($anggota->tanggal_bergabung)
          <dt class="col-sm-4 text-muted">Bergabung</dt>
          <dd class="col-sm-8">{{ $anggota->tanggal_bergabung->format('d/m/Y') }}</dd>
        @endif
        @if ($anggota->status === 'nonaktif')
          <dt class="col-sm-4 text-muted">Tanggal Undur</dt>
          <dd class="col-sm-8">{{ $anggota->tanggal_undur?->format('d/m/Y') ?: '-' }}</dd>
          @if ($anggota->alasan_undur)
            <dt class="col-sm-4 text-muted">Alasan</dt>
            <dd class="col-sm-8">{{ $anggota->alasan_undur }}</dd>
          @endif
        @endif
      </dl>

      <div class="d-flex flex-wrap gap-2 mt-4">
        @if ($anggota->status === 'aktif')
          <a href="{{ route('kta.cetak', $anggota->nomor_anggota) }}" class="btn btn-nu" target="_blank">
            <i class="bi bi-printer me-1"></i> Cetak KTA
          </a>
        @endif
        <a href="{{ route('kta.index') }}" class="btn btn-light">Kembali ke KTA</a>
      </div>

      @if (session('success'))
        <p class="meta mt-3 mb-0"><strong>Simpan nomor anggota:</strong> <span class="font-monospace">{{ $anggota->nomor_anggota }}</span> — gunakan untuk cek/cetak KTA nanti.</p>
      @endif
    </div>
  </div>
</section>
@endsection
