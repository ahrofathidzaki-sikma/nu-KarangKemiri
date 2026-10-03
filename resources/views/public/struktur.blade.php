@extends('layouts.public')
@section('title', 'Struktur Organisasi')
@section('content')
<header class="page-head"><div class="container"><div class="eyebrow text-white-50">Kepengurusan</div><h1>Struktur Organisasi</h1></div></header>
<section class="section"><div class="container">

  {{-- IPNU (Laki-laki) --}}
  <div class="mb-5">
    <h2 class="h4 mb-3 text-success"><i class="bi bi-people-fill me-2"></i>IPNU <span class="text-muted fw-normal fs-6">(Ikatan Pelajar Nahdlatul Ulama)</span></h2>
    <div class="row g-4 justify-content-center">
      @forelse ($ipnu as $p)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card-nu person">
            @if ($p->foto_url)<img src="{{ $p->foto_url }}" class="avatar" alt="{{ $p->nama }}">@else<div class="avatar">{{ strtoupper(substr($p->nama, 0, 1)) }}</div>@endif
            <h3 class="h6 mb-1">{{ $p->nama }}</h3>
            <div class="text-success fw-semibold small">{{ $p->jabatan }}</div>
            @if ($p->periode)<div class="meta">Periode {{ $p->periode }}</div>@endif
            @if ($p->nomor_wa)
              <div class="meta mt-1">
                <a href="{{ $p->wa_link }}" target="_blank" rel="noopener" class="text-decoration-none text-success">
                  <i class="bi bi-whatsapp"></i> {{ $p->nomor_wa }}
                </a>
              </div>
            @endif
            @if ($p->keterangan)<p class="meta mt-2 mb-0">{{ $p->keterangan }}</p>@endif
          </div>
        </div>
      @empty
        <div class="col-12"><div class="alert alert-light border">Data pengurus IPNU belum tersedia.</div></div>
      @endforelse
    </div>
  </div>

  {{-- IPPNU (Perempuan) --}}
  <div class="mb-3">
    <h2 class="h4 mb-3 text-info"><i class="bi bi-people-fill me-2"></i>IPPNU <span class="text-muted fw-normal fs-6">(Ikatan Pelajar Putri Nahdlatul Ulama)</span></h2>
    <div class="row g-4 justify-content-center">
      @forelse ($ippnu as $p)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card-nu person">
            @if ($p->foto_url)<img src="{{ $p->foto_url }}" class="avatar" alt="{{ $p->nama }}">@else<div class="avatar">{{ strtoupper(substr($p->nama, 0, 1)) }}</div>@endif
            <h3 class="h6 mb-1">{{ $p->nama }}</h3>
            <div class="text-info fw-semibold small">{{ $p->jabatan }}</div>
            @if ($p->periode)<div class="meta">Periode {{ $p->periode }}</div>@endif
            @if ($p->nomor_wa)
              <div class="meta mt-1">
                <a href="{{ $p->wa_link }}" target="_blank" rel="noopener" class="text-decoration-none text-success">
                  <i class="bi bi-whatsapp"></i> {{ $p->nomor_wa }}
                </a>
              </div>
            @endif
            @if ($p->keterangan)<p class="meta mt-2 mb-0">{{ $p->keterangan }}</p>@endif
          </div>
        </div>
      @empty
        <div class="col-12"><div class="alert alert-light border">Data pengurus IPPNU belum tersedia.</div></div>
      @endforelse
    </div>
  </div>

</div></section>
@endsection
