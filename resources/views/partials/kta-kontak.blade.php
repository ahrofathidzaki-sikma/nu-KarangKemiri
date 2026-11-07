@php
  $tel = preg_replace('/\D+/', '', (string) $pengaturan->telepon);
  $waTujuan = ($tel !== '' && (str_starts_with($tel, '08') || str_starts_with($tel, '628')))
      ? (str_starts_with($tel, '0') ? '62' . substr($tel, 1) : $tel) : null;
  $pesan = rawurlencode('Assalamualaikum, saya ingin bertanya tentang KTA anggota NU ' . $pengaturan->nama_desa . '.');
@endphp
@if ($tel !== '' || $pengaturan->alamat)
  <div class="kta-kontak">
    <div class="fw-bold mb-1"><i class="bi bi-headset text-success"></i> Masih kesulitan? Pengurus siap membantu</div>
    <p class="small mb-3">Sebutkan nama lengkap Anda. Pengurus dapat mencarikan nomor anggota Anda.</p>
    <div class="d-flex flex-wrap gap-2">
      @if ($waTujuan)<a class="btn btn-nu" href="https://wa.me/{{ $waTujuan }}?text={{ $pesan }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> Chat WhatsApp pengurus</a>@endif
      @if ($tel !== '')<a class="btn btn-light" href="tel:{{ $tel }}"><i class="bi bi-telephone"></i> Telepon {{ $pengaturan->telepon }}</a>@endif
    </div>
    @if ($pengaturan->alamat)<p class="small mt-3 mb-0"><i class="bi bi-geo-alt"></i> Atau datang langsung: {{ $pengaturan->alamat }}</p>@endif
  </div>
@endif
