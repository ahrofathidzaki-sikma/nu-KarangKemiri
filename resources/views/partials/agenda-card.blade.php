<div class="card-nu p-3 d-flex gap-3 align-items-start position-relative">
  <div class="date-chip"><b>{{ $a->tanggal->format('d') }}</b><small>{{ $a->tanggal->translatedFormat('M Y') }}</small></div>
  <div class="flex-grow-1">
    <span class="badge-status {{ $a->status === 'Selesai' ? 'badge-done' : 'badge-soon' }}">{{ $a->status }}</span>
    <h3 class="h6 mt-2 mb-1"><a href="{{ route('agenda.show', $a) }}" class="stretched-link text-decoration-none text-reset">{{ $a->judul }}</a></h3>
    <div class="meta"><i class="bi bi-clock"></i> {{ $a->waktu ? $a->waktu.' WIB' : '-' }} &middot; <i class="bi bi-geo-alt"></i> {{ $a->lokasi }}</div>
  </div>
</div>
