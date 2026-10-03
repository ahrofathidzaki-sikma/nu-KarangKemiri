@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="row g-3 mb-4">
  @foreach ([['Total Agenda',$totalAgenda,'bi-calendar-event'],['Total Berita',$totalBerita,'bi-newspaper'],['Pengurus',$totalPengurus,'bi-diagram-3'],['Anggota Aktif',$totalAnggotaAktif,'bi-person-vcard'],['Dokumentasi',$totalDokumentasi,'bi-images']] as [$l,$n,$i])
    <div class="col-6 col-md-4 col-xl"><div class="admin-card kpi"><div class="ic"><i class="bi {{ $i }}"></i></div><div><b>{{ $n }}</b><br><span>{{ $l }}</span></div></div></div>
  @endforeach

</div>
<div class="row g-3">
  <div class="col-lg-6"><div class="admin-card p-3 h-100">
    <div class="d-flex justify-content-between mb-2"><h2 class="h6 mb-0">Agenda Akan Datang</h2><a href="{{ route('admin.agendas.create') }}" class="small text-decoration-none">+ Tambah</a></div>
    @forelse ($agendaAkanDatang as $a)
      <div class="d-flex gap-3 py-2 border-top align-items-center">
        <div class="date-chip" style="width:52px"><b style="font-size:1.2rem">{{ $a->tanggal->format('d') }}</b><small>{{ $a->tanggal->translatedFormat('M') }}</small></div>
        <div class="flex-grow-1"><div class="fw-semibold">{{ $a->judul }}</div><div class="meta">{{ $a->lokasi }}</div></div>
        <a href="{{ route('admin.agendas.edit', $a) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
      </div>
    @empty<p class="meta mb-0">Belum ada agenda akan datang.</p>@endforelse
  </div></div>
  <div class="col-lg-6"><div class="admin-card p-3 h-100">
    <div class="d-flex justify-content-between mb-2"><h2 class="h6 mb-0">Berita Terbaru</h2><a href="{{ route('admin.berita.create') }}" class="small text-decoration-none">+ Tambah</a></div>
    @forelse ($beritaTerbaru as $b)
      <div class="d-flex gap-3 py-2 border-top align-items-center">
        <div class="flex-grow-1"><div class="fw-semibold">{{ $b->judul }}</div><div class="meta">{{ $b->tanggal->translatedFormat('d F Y') }} &middot; {{ $b->penulis }}</div></div>
        <a href="{{ route('admin.berita.edit', $b) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
      </div>
    @empty<p class="meta mb-0">Belum ada berita.</p>@endforelse
  </div></div>
</div>
@endsection
