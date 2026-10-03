<div class="row g-3">
  @forelse ($items as $d)
    <div class="col-6 col-md-4 col-lg-3">
      @if ($d->gambar_url)
        <a href="{{ $d->gambar_url }}" target="_blank" class="gallery-item"><img src="{{ $d->gambar_url }}" alt="{{ $d->judul }}" loading="lazy"><span class="cap">{{ $d->judul }}</span></a>
      @else
        <div class="gallery-item"><div class="ph ph-square w-100 h-100"><i class="bi bi-camera"></i></div><span class="cap">{{ $d->judul }}</span></div>
      @endif
    </div>
  @empty
    <div class="col-12"><div class="alert alert-light border">Belum ada dokumentasi.</div></div>
  @endforelse
</div>
