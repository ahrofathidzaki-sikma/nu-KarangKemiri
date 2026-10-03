@extends('layouts.public')
@section('title', $agenda->judul)
@section('content')
<header class="page-head"><div class="container">
  <a href="{{ route('agenda.index') }}" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left"></i> Semua agenda</a>
  <h1 class="mt-2">{{ $agenda->judul }}</h1>
  <span class="badge-status {{ $agenda->status === 'Selesai' ? 'badge-done' : 'badge-soon' }}">{{ $agenda->status }}</span>
</div></header>
<section class="section"><div class="container"><div class="row g-4">
  <div class="col-lg-8">
    <div class="article-body mb-4">{!! nl2br(e($agenda->deskripsi ?: 'Belum ada deskripsi.')) !!}</div>
    <h2 class="h4 mb-3">Dokumentasi</h2>
    @include('partials.gallery', ['items' => $agenda->dokumentasis])
  </div>
  <div class="col-lg-4"><div class="admin-card p-4">
    <div class="mb-3"><div class="meta">Tanggal</div><strong>{{ $agenda->tanggal->translatedFormat('l, d F Y') }}</strong></div>
    <div class="mb-3"><div class="meta">Waktu</div><strong>{{ $agenda->waktu ? $agenda->waktu.' WIB' : '-' }}</strong></div>
    <div><div class="meta">Lokasi</div><strong>{{ $agenda->lokasi }}</strong></div>
  </div></div>
</div></div></section>
@endsection
