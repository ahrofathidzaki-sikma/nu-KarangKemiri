@extends('layouts.public')
@section('title', 'Berita')
@section('content')
<header class="page-head"><div class="container"><div class="eyebrow text-white-50">Warta</div><h1>Berita</h1></div></header>
<section class="section"><div class="container">
  <div class="row g-4">
    @forelse ($beritas as $b)<div class="col-md-6 col-lg-4">@include('partials.berita-card', ['b' => $b])</div>
    @empty<div class="col-12"><div class="alert alert-light border">Belum ada berita.</div></div>@endforelse
  </div>
  <div class="mt-4 d-flex justify-content-center">{{ $beritas->links() }}</div>
</div></section>
@endsection
