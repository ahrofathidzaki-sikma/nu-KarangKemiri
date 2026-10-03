@extends('layouts.public')
@section('title', 'Dokumentasi')
@section('content')
<header class="page-head"><div class="container"><div class="eyebrow text-white-50">Galeri</div><h1>Dokumentasi Kegiatan</h1></div></header>
<section class="section"><div class="container">
  <form method="GET" class="row g-2 mb-4">
    <div class="col-sm-6 col-md-4"><select name="agenda" class="form-select" onchange="this.form.submit()">
      <option value="">Semua kegiatan</option>
      @foreach ($agendas as $a)<option value="{{ $a->id }}" @selected(request('agenda') == $a->id)>{{ $a->judul }}</option>@endforeach
    </select></div>
  </form>
  @include('partials.gallery', ['items' => $dokumentasis])
  <div class="mt-4 d-flex justify-content-center">{{ $dokumentasis->links() }}</div>
</div></section>
@endsection
