@extends('layouts.public')
@section('title', 'Agenda')
@section('content')
<header class="page-head"><div class="container"><div class="eyebrow text-white-50">Kegiatan</div><h1>Agenda</h1></div></header>
<section class="section"><div class="container">
  <h2 class="h3 mb-3"><i class="bi bi-calendar-event text-success"></i> Kegiatan yang Akan Datang</h2>
  <div class="row g-3 mb-5">
    @forelse ($akanDatang as $a)<div class="col-md-6 col-lg-4">@include('partials.agenda-card', ['a' => $a])</div>
    @empty<div class="col-12"><div class="alert alert-light border">Belum ada kegiatan yang akan datang.</div></div>@endforelse
  </div>
  <h2 class="h3 mb-3"><i class="bi bi-calendar-check text-secondary"></i> Kegiatan yang Sudah Berlalu</h2>
  <div class="row g-3">
    @forelse ($selesai as $a)<div class="col-md-6 col-lg-4">@include('partials.agenda-card', ['a' => $a])</div>
    @empty<div class="col-12"><div class="alert alert-light border">Belum ada kegiatan yang selesai.</div></div>@endforelse
  </div>
  <div class="mt-4 d-flex justify-content-center">{{ $selesai->links() }}</div>
</div></section>
@endsection
