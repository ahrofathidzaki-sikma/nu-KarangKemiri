<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#08382a">
  <script>document.documentElement.classList.add('js')</script>
  <title>@yield('title', 'Beranda') — {{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}</title>
  <meta name="description" content="{{ \Illuminate\Support\Str::limit($pengaturan->deskripsi, 150) }}">
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/efek.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg site-nav sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('beranda') }}">
      <span class="brand-mark">@if ($pengaturan->logo_url)<img src="{{ $pengaturan->logo_url }}" alt="Logo">@else<i class="bi bi-moon-stars-fill"></i>@endif</span>
      <span><span class="brand-title d-block">{{ $pengaturan->nama_organisasi }}</span><span class="brand-sub">{{ $pengaturan->nama_desa }}</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Menu"><i class="bi bi-list fs-1"></i></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto gap-lg-1 py-2 py-lg-0">
        @foreach ([['beranda','Beranda','beranda'],['tentang','Tentang','tentang'],['struktur','Struktur','struktur'],['kta.index','KTA','kta*'],['berita.index','Berita','berita*'],['agenda.index','Agenda','agenda*'],['dokumentasi','Dokumentasi','dokumentasi']] as [$r, $label, $pat])
          <li class="nav-item"><a class="nav-link {{ request()->routeIs($pat) ? 'active' : '' }}" href="{{ route($r) }}">{{ $label }}</a></li>
        @endforeach

        <li class="nav-item ms-lg-2"><a class="btn btn-nu btn-sm mt-2 mt-lg-0" href="{{ route('login') }}"><i class="bi bi-person-lock"></i> Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<main>@yield('content')</main>

<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5">
        <h5>Tentang</h5>
        <p>{{ $pengaturan->deskripsi }}</p>
        <p class="small mb-0">{{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}</p>
      </div>
      <div class="col-md-6 col-lg-4">
        <h5>Contact Us</h5>
        <ul class="list-unstyled mb-0">
          @if ($pengaturan->alamat)<li class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $pengaturan->alamat }}</li>@endif
          @if ($pengaturan->telepon)<li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $pengaturan->telepon }}</li>@endif
          @if ($pengaturan->email)<li><i class="bi bi-envelope me-2"></i><a href="mailto:{{ $pengaturan->email }}">{{ $pengaturan->email }}</a></li>@endif
        </ul>
      </div>
      <div class="col-md-6 col-lg-3">
        <h5>Social Media</h5>
        <div class="social">
          @if ($pengaturan->instagram)<a href="{{ $pengaturan->instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>@endif
          @if ($pengaturan->facebook)<a href="{{ $pengaturan->facebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>@endif
          @if ($pengaturan->youtube)<a href="{{ $pengaturan->youtube }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>@endif
        </div>
      </div>
    </div>
    <div class="footer-bottom text-center">&copy; {{ date('Y') }} {{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}. Hak cipta dilindungi.</div>
  </div>
</footer>
<nav class="bottom-nav d-lg-none" aria-label="Navigasi cepat">
  @foreach ([['beranda','bi-house-door','Beranda','beranda'],['agenda.index','bi-calendar-event','Agenda','agenda*'],['kta.index','bi-person-vcard','KTA','kta*'],['dokumentasi','bi-images','Galeri','dokumentasi'],['struktur','bi-diagram-3','Struktur','struktur']] as [$r, $ic, $lb, $pt])
    <a href="{{ route($r) }}" class="{{ request()->routeIs($pt) ? 'active' : '' }}"><i class="bi {{ $ic }}"></i><span>{{ $lb }}</span></a>
  @endforeach

</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/efek.js') }}" defer></script>
</body>
</html>
