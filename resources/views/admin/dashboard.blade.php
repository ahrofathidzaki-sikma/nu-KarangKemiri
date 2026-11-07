@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

{{-- Sapaan --}}
<div class="dash-hero mb-3">
  <div>
    <h2 class="dash-nama">{{ auth()->user()->name }}</h2>
    <p class="dash-sub">{{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}<br>{{ now()->translatedFormat('l, d F Y') }}</p>
  </div>
</div>

{{-- Ringkasan angka (klik untuk membuka daftarnya) --}}
<div class="kpi-grid mb-3">
  @foreach ([
    ['Agenda', $totalAgenda, 'bi-calendar-event', 'admin.agendas.index', 'hijau', null],
    ['Berita', $totalBerita, 'bi-newspaper', 'admin.berita.index', 'biru', null],
    ['Pengurus', $totalPengurus, 'bi-diagram-3', 'admin.anggota.index', 'kuning', null],
    ['Anggota aktif', $totalAnggotaAktif, 'bi-person-vcard', 'admin.kta.index', 'tosca', $totalAnggota > $totalAnggotaAktif ? 'dari ' . $totalAnggota . ' terdaftar' : null],
    ['Dokumentasi', $totalDokumentasi, 'bi-images', 'admin.dokumentasi.index', 'ungu', null],
  ] as [$label, $angka, $ikon, $rute, $warna, $sub])
    <a href="{{ route($rute) }}" class="kpi-card w-{{ $warna }}">
      <span class="ik"><i class="bi {{ $ikon }}"></i></span>
      <b>{{ $angka }}</b>
      <span class="lb">{{ $label }}</span>
      @if ($sub)<small>{{ $sub }}</small>@endif
      <i class="bi bi-arrow-up-right go"></i>
    </a>
  @endforeach
</div>

{{-- Perlu perhatian --}}
@if ($anggotaTanpaTglLahir > 0)
  <div class="perhatian mb-3">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div><strong>{{ $anggotaTanpaTglLahir }} anggota belum punya tanggal lahir.</strong><br>
      <span>Mereka belum bisa membuka KTA sendiri di website.</span></div>
    <a href="{{ route('admin.kta.index') }}">Lengkapi</a>
  </div>
@endif

<div class="row g-3">
  {{-- Agenda --}}
  <div class="col-xl-4 col-lg-6">
    <section class="panel">
      <header><h3>Agenda Akan Datang</h3><a href="{{ route('admin.agendas.create') }}" class="plus"><i class="bi bi-plus-lg"></i> Tambah</a></header>
      @forelse ($agendaAkanDatang as $a)
        <a href="{{ route('admin.agendas.edit', $a) }}" class="baris">
          <span class="tgl"><b>{{ $a->tanggal->format('d') }}</b><small>{{ $a->tanggal->translatedFormat('M') }}</small></span>
          <span class="teks"><strong>{{ $a->judul }}</strong><small>{{ $a->waktu ? $a->waktu . ' WIB · ' : '' }}{{ $a->lokasi }}</small></span>
          <i class="bi bi-chevron-right"></i>
        </a>
      @empty
        <div class="kosong"><i class="bi bi-calendar2-x"></i><p>Belum ada agenda mendatang.</p><a href="{{ route('admin.agendas.create') }}" class="aksi">Buat agenda</a></div>
      @endforelse
    </section>
  </div>

  {{-- Berita --}}
  <div class="col-xl-4 col-lg-6">
    <section class="panel">
      <header><h3>Berita Terbaru</h3><a href="{{ route('admin.berita.create') }}" class="plus"><i class="bi bi-plus-lg"></i> Tambah</a></header>
      @forelse ($beritaTerbaru as $b)
        <a href="{{ route('admin.berita.edit', $b) }}" class="baris">
          <span class="teks"><strong>{{ $b->judul }}</strong><small>{{ $b->tanggal->translatedFormat('d F Y') }} · {{ $b->penulis }}</small></span>
          <i class="bi bi-chevron-right"></i>
        </a>
      @empty
        <div class="kosong"><i class="bi bi-newspaper"></i><p>Belum ada berita.</p><a href="{{ route('admin.berita.create') }}" class="aksi">Tulis berita</a></div>
      @endforelse
    </section>
  </div>

  {{-- Anggota terbaru --}}
  <div class="col-xl-4 col-lg-12">
    <section class="panel">
      <header><h3>Anggota Terbaru</h3><a href="{{ route('admin.kta.create') }}" class="plus"><i class="bi bi-plus-lg"></i> Tambah</a></header>
      @forelse ($anggotaBaru as $m)
        <a href="{{ route('admin.kta.edit', $m) }}" class="baris">
          <span class="av">{{ strtoupper(mb_substr($m->nama, 0, 1)) }}</span>
          <span class="teks"><strong>{{ $m->nama }}</strong><small>{{ $m->organisasi_label }} · {{ $m->nomor_anggota }}</small></span>
          <i class="bi bi-chevron-right"></i>
        </a>
      @empty
        <div class="kosong"><i class="bi bi-people"></i><p>Belum ada anggota.</p><a href="{{ route('admin.kta.create') }}" class="aksi">Tambah anggota</a></div>
      @endforelse
    </section>
  </div>
</div>
@endsection
