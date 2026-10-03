<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard') — Admin {{ $pengaturan->nama_desa }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
@php
  $menu = [
    ['admin.dashboard', 'Dashboard', 'bi-speedometer2', 'admin.dashboard'],
    ['admin.agendas.index', 'Agenda', 'bi-calendar-event', 'admin.agendas.*'],
    ['admin.berita.index', 'Berita', 'bi-newspaper', 'admin.berita.*'],
    ['admin.anggota.index', 'Struktur Organisasi', 'bi-diagram-3', 'admin.anggota.*'],
    ['admin.dokumentasi.index', 'Dokumentasi', 'bi-images', 'admin.dokumentasi.*'],
    ['admin.pengaturan.edit', 'Pengaturan', 'bi-gear', 'admin.pengaturan.*'],
  ];
@endphp
<div class="sidebar-wrap">
  <div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
      <span class="brand-mark" style="background:var(--nu-500)">@if ($pengaturan->logo_url)<img src="{{ $pengaturan->logo_url }}" alt="">@else<i class="bi bi-moon-stars-fill"></i>@endif</span>
      <div class="lh-sm"><div class="fw-bold font-head">{{ $pengaturan->nama_organisasi }}</div><small class="opacity-75">{{ $pengaturan->nama_desa }}</small></div>
      <button class="btn-close btn-close-white ms-auto d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
    </div>
    <nav class="nav flex-column py-3 flex-grow-1">
      @foreach ($menu as [$route, $label, $icon, $pat])
        <a class="nav-link {{ request()->routeIs($pat) ? 'active' : '' }}" href="{{ route($route) }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>
      @endforeach
    </nav>
    <div class="sidebar-foot">
      <a class="nav-link" href="{{ route('beranda') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i>Lihat Website</a>
      <form method="POST" action="{{ route('admin.logout') }}">@csrf
        <button type="submit" class="nav-link logout"><i class="bi bi-box-arrow-left"></i>Logout</button>
      </form>
    </div>
  </div>
</div>

<div class="admin-main">
  <div class="admin-top d-flex align-items-center gap-3 sticky-top">
    <button class="btn btn-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="Menu"><i class="bi bi-list fs-5"></i></button>
    <h1 class="h5 mb-0 flex-grow-1">@yield('title', 'Dashboard')</h1>
    <span class="small text-muted d-none d-sm-inline"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</span>
  </div>
  <div class="p-3 p-lg-4">
    @include('partials.alerts')
    @yield('content')
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
