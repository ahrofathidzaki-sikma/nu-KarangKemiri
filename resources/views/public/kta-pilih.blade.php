@extends('layouts.public')
@section('title', 'Pilih Data Anda — KTA')
@section('content')
<link href="{{ asset('css/kta.css') }}" rel="stylesheet">
<header class="page-head">
  <div class="container">
    <h1>Yang mana data Anda?</h1>
    <p class="lead text-white-50 mb-0">Ditemukan beberapa data dengan nama mirip. Pilih yang sesuai.</p>
  </div>
</header>
<section class="section kta-page">
  <div class="container" style="max-width:700px">
    @foreach ($anggotas as $a)
      <form method="POST" action="{{ route('kta.lupa.pilih.store') }}" class="pilihan-kta mb-3 d-flex flex-wrap gap-3 align-items-center justify-content-between">
        @csrf
        <input type="hidden" name="id" value="{{ $a->id }}">
        <div>
          <div class="fw-bold fs-5">{{ $a->nama }}</div>
          <div class="small text-muted">
            {{ $a->organisasi_label }} · {{ $a->jenis_kelamin_label }}
            @if ($a->tanggal_bergabung) · bergabung {{ $a->tanggal_bergabung->translatedFormat('F Y') }}@endif
            @if ($a->wa_samar) · WA {{ $a->wa_samar }}@endif
          </div>
        </div>
        <button class="btn btn-nu"><i class="bi bi-check2-circle"></i> Ini data saya</button>
      </form>
    @endforeach
    <a href="{{ route('kta.lupa') }}" class="btn btn-light mt-2"><i class="bi bi-arrow-left"></i> Cari lagi</a>
    <div class="mt-4">@include('partials.kta-kontak')</div>
  </div>
</section>
@endsection
