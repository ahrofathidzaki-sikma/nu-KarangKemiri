@extends('layouts.public')
@section('title', 'Dokumentasi')
@section('content')
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Galeri</div>
    <h1>Dokumentasi Kegiatan</h1>
  </div>
</header>
<section class="section section-galeri">
  <div class="container">
    <div class="mb-3">
      <div class="small text-muted mb-2">Filter kegiatan</div>
      <div class="filter-chips">
        <a href="{{ route('dokumentasi') }}"
           class="chip {{ !request('agenda') ? 'active' : '' }}">Semua</a>
        @foreach ($agendas as $a)
          <a href="{{ route('dokumentasi', ['agenda' => $a->id]) }}"
             class="chip {{ request('agenda') == $a->id ? 'active' : '' }}">
            {{ $a->judul }} · {{ $a->tanggal?->translatedFormat('d M Y') }}
          </a>
        @endforeach
      </div>
    </div>
    @include('partials.gallery', ['items' => $dokumentasis])
    <div class="mt-4 d-flex justify-content-center">{{ $dokumentasis->links() }}</div>
  </div>
</section>
@endsection
