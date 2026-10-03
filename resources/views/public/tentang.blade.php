@extends('layouts.public')
@section('title', 'Tentang')
@section('content')
<header class="page-head"><div class="container"><div class="eyebrow text-white-50">Profil</div><h1>Tentang Kami</h1></div></header>
<section class="section">
  <div class="container"><div class="row g-5">
    <div class="col-lg-7">
      <h2 class="h3 text-success-emphasis">Profil Organisasi</h2>
      <p class="article-body">{{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }} adalah struktur Nahdlatul Ulama tingkat desa di {{ $pengaturan->kabupaten }}, Jawa Tengah. {{ $pengaturan->deskripsi }}</p>
      <h2 class="h3 text-success-emphasis mt-4">Sejarah Singkat</h2>
      <p class="article-body">Nahdlatul Ulama didirikan oleh para ulama pada 31 Januari 1926 (16 Rajab 1344 H) di Surabaya untuk menjaga ajaran Ahlussunnah wal Jamaah. Di tingkat desa, ranting hadir sebagai ujung tombak khidmat kepada warga melalui pengajian, kegiatan sosial, dan pembinaan generasi muda. <em>(Teks ini dapat disesuaikan dengan sejarah ranting yang sebenarnya.)</em></p>
    </div>
    <div class="col-lg-5">
      <div class="admin-card p-4 mb-3"><h2 class="h5">Visi</h2><p class="mb-0">Terwujudnya masyarakat desa yang berakhlak mulia, mandiri, dan rukun dalam bingkai Islam Ahlussunnah wal Jamaah an-Nahdliyah.</p></div>
      <div class="admin-card p-4"><h2 class="h5">Misi</h2>
        <ul class="mb-0 ps-3"><li>Menyelenggarakan pengajian dan pendidikan keagamaan.</li><li>Memperkuat ukhuwah dan kegiatan sosial warga.</li><li>Membina generasi muda dan perempuan.</li><li>Menyajikan informasi kegiatan secara terbuka.</li></ul></div>
      <div class="ayat mt-3"><strong>Informasi umum</strong><br>{{ $pengaturan->alamat }}<br>{{ $pengaturan->telepon }} &middot; {{ $pengaturan->email }}</div>
    </div>
  </div></div>
</section>
@endsection
