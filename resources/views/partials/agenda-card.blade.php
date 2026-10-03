<div class="card-nu agenda-card d-flex gap-3 position-relative">
  <div class="date-chip {{ $a->status === 'Selesai' ? 'done' : '' }}">
    <small class="dc-day">{{ $a->tanggal->translatedFormat('D') }}</small>
    <b>{{ $a->tanggal->format('d') }}</b>
    <small>{{ $a->tanggal->translatedFormat('M Y') }}</small>
  </div>
  <div class="flex-grow-1" style="min-width:0">
    <span class="badge-status {{ $a->status === 'Selesai' ? 'badge-done' : 'badge-soon' }}">{{ $a->status }}</span>
    <h3><a href="{{ route('agenda.show', $a) }}" class="stretched-link text-decoration-none text-reset">{{ $a->judul }}</a></h3>
    <div class="meta-row">
      <span class="meta-pill"><i class="bi bi-clock"></i>{{ $a->waktu ? $a->waktu.' WIB' : 'Sepanjang hari' }}</span>
      <span class="meta-pill"><i class="bi bi-geo-alt"></i>{{ $a->lokasi }}</span>
    </div>
  </div>
  <span class="go" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
</div>
