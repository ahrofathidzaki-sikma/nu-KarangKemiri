@extends('layouts.public')
@section('title', $berita->judul)
@section('content')
<header class="page-head"><div class="container" style="max-width:860px">
  <a href="{{ route('berita.index') }}" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left"></i> Semua berita</a>
  <h1 class="mt-2">{{ $berita->judul }}</h1>
  <div class="text-white-50 small"><i class="bi bi-calendar3"></i> {{ $berita->tanggal->translatedFormat('l, d F Y') }} &middot; <i class="bi bi-person"></i> {{ $berita->penulis }}</div>
</div></header>
<section class="section"><div class="container" style="max-width:860px">
  @if ($berita->gambar_url)<img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="w-100 rounded-4 mb-4">@endif
  <div class="article-body">{!! nl2br(e($berita->isi)) !!}</div>
</div></section>
@if ($lainnya->count())
<section class="section section-alt"><div class="container">
  <h2 class="h4 mb-4">Berita lainnya</h2>
  <div class="row g-4">@foreach ($lainnya as $b)<div class="col-md-4">@include('partials.berita-card', ['b' => $b])</div>@endforeach</div>
</div></section>
@endif
@endsection
