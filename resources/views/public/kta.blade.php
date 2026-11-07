@extends('layouts.public')
@section('title', 'KTA — Kartu Tanda Anggota')
@section('content')
<link href="{{ asset('css/kta.css') }}" rel="stylesheet">
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Keanggotaan</div>
    <h1>Kartu Tanda Anggota (KTA)</h1>
    <p class="lead text-white-50 mb-0">Pilih sesuai keadaan Anda. Kami pandu langkah demi langkah.</p>
  </div>
</header>

<section class="section kta-page">
  <div class="container">
    @include('partials.kta-alert')

    <div class="row g-4">
      {{-- Sudah punya nomor --}}
      <div class="col-lg-4">
        <div class="opsi-kta utama">
          <div class="ikon"><i class="bi bi-credit-card-2-front"></i></div>
          <h2>Saya sudah punya nomor</h2>
          <p class="sub">Buka dan cetak KTA Anda.</p>
          <form method="POST" action="{{ route('kta.cek') }}" novalidate>
            @csrf
            <div class="mb-3">
              <label class="form-label" for="kunci">Nomor anggota atau NIK</label>
              <input id="kunci" type="text" name="kunci" value="{{ old('kunci') }}" autocomplete="off"
                     class="form-control @error('kunci') is-invalid @enderror" placeholder="Contoh: NU-001">
              @error('kunci')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">@include('partials.tanggal-lahir', ['label' => 'Tanggal lahir Anda'])</div>
            <button type="submit" class="btn btn-nu w-100"><i class="bi bi-unlock"></i> Buka KTA saya</button>
          </form>
        </div>
      </div>

      {{-- Lupa nomor --}}
      <div class="col-lg-4">
        <div class="opsi-kta">
          <div class="ikon"><i class="bi bi-search-heart"></i></div>
          <h2>Saya lupa nomor</h2>
          <p class="sub">Tenang, ini hal yang wajar. Cukup ingat nama dan tanggal lahir Anda.</p>
          <ol class="langkah mb-4">
            <li>Ketik nama lengkap Anda</li>
            <li>Isi tanggal lahir (atau nomor WA yang didaftarkan)</li>
            <li>Nomor anggota Anda langsung ditampilkan</li>
          </ol>
          <a href="{{ route('kta.lupa') }}" class="btn btn-outline-success w-100"><i class="bi bi-search"></i> Cari nomor saya</a>
        </div>
      </div>

      {{-- Belum terdaftar --}}
      <div class="col-lg-4">
        <div class="opsi-kta">
          <div class="ikon"><i class="bi bi-person-plus"></i></div>
          <h2>Saya belum terdaftar</h2>
          <p class="sub">Daftar menjadi anggota NU di {{ $pengaturan->nama_desa }}.</p>
          <p class="small text-muted">Hanya perlu beberapa menit. Setelah selesai, nomor anggota langsung muncul dan KTA bisa dicetak.</p>
          <a href="{{ route('kta.daftar') }}" class="btn btn-nu w-100 mt-2"><i class="bi bi-pencil-square"></i> Daftar anggota baru</a>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-1">
      <div class="col-lg-7">
        <h2 class="h4 mb-3">Pertanyaan yang sering diajukan</h2>
        <div class="accordion kta-faq" id="faqKta">
          @foreach ([
            ['Apa gunanya KTA?', 'KTA adalah kartu identitas keanggotaan Anda di ' . $pengaturan->nama_organisasi . ' ' . $pengaturan->nama_desa . '. Bisa dicetak atau disimpan sebagai PDF dan ditunjukkan saat kegiatan atau keperluan organisasi.'],
            ['Mengapa diminta tanggal lahir?', 'Tanggal lahir berfungsi sebagai kunci keamanan, supaya data pribadi Anda tidak bisa dibuka orang lain hanya dengan menebak nomor. Tanggal lahir juga membantu menemukan nomor Anda bila lupa.'],
            ['Nomor saya tetap tidak ketemu, bagaimana?', 'Bisa jadi tanggal lahir Anda belum tercatat (misalnya data didaftarkan oleh pengurus). Hubungi pengurus lewat tombol bantuan di bawah, sebutkan nama lengkap Anda.'],
            ['Bagaimana agar tidak lupa lagi?', 'Setelah KTA terbuka, tekan "Kirim nomor ke WhatsApp saya" agar nomor tersimpan di chat Anda. Anda juga bisa memotret layar atau mencetak KTA dan menyimpannya di dompet.'],
          ] as $i => [$tanya, $jawab])
            <div class="accordion-item">
              <h3 class="accordion-header"><button class="accordion-button {{ $i ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $tanya }}</button></h3>
              <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faqKta"><div class="accordion-body">{{ $jawab }}</div></div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-5">@include('partials.kta-kontak')</div>
    </div>
  </div>
</section>
@endsection
