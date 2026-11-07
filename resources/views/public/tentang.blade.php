@extends('layouts.public')
@section('title', 'Tentang')
@section('content')
<header class="page-head">
  <div class="container">
    <div class="eyebrow text-white-50">Profil</div>
    <h1>Tentang Kami</h1>
    <p class="text-white-50 mb-0 mt-2">{{ $pengaturan->nama_organisasi }} · {{ $pengaturan->nama_desa }}</p>
  </div>
</header>

<section class="section tentang-section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="tentang-block mb-4">
          <h2 class="h4 text-success mb-3">Profil Organisasi</h2>
          <p class="mb-0 lh-lg">
            {{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }} merupakan struktur Nahdlatul Ulama di tingkat desa.
            @if($pengaturan->deskripsi) {{ $pengaturan->deskripsi }} @endif
          </p>
        </div>
        <div class="tentang-block">
          <h2 class="h4 text-success mb-3">Sejarah Singkat</h2>
          <p class="mb-0 lh-lg">
            Nahdlatul Ulama didirikan para ulama pada 31 Januari 1926 di Surabaya untuk menjaga ajaran Ahlussunnah wal Jamaah.
            Di tingkat desa, ranting menjadi ujung tombak khidmat melalui pengajian, kegiatan sosial, dan pembinaan generasi muda.
          </p>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="tentang-side mb-3">
          <div class="eyebrow text-success mb-2">Visi</div>
          <p class="mb-0">Terwujudnya masyarakat desa yang berakhlak mulia, mandiri, dan rukun dalam bingkai Islam Ahlussunnah wal Jamaah an-Nahdliyah.</p>
        </div>
        <div class="tentang-side mb-3">
          <div class="eyebrow text-success mb-2">Misi</div>
          <ul class="mb-0 ps-3 lh-lg">
            <li>Menyelenggarakan pengajian dan pendidikan keagamaan</li>
            <li>Memperkuat ukhuwah dan kegiatan sosial warga</li>
            <li>Membina generasi muda dan perempuan (IPNU &amp; IPPNU)</li>
            <li>Menyajikan informasi kegiatan secara terbuka</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
