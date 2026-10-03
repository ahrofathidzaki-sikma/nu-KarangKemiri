<article class="card-nu position-relative">
  @include('partials.media', ['url' => $b->gambar_url, 'alt' => $b->judul])
  <div class="p-3">
    <div class="meta mb-1"><i class="bi bi-calendar3"></i> {{ $b->tanggal->translatedFormat('d F Y') }} &middot; {{ $b->penulis }}</div>
    <h3 class="h5"><a href="{{ route('berita.show', $b->slug) }}" class="stretched-link text-decoration-none text-reset">{{ $b->judul }}</a></h3>
    <p class="meta mb-0">{{ $b->ringkasan }}</p>
  </div>
</article>
