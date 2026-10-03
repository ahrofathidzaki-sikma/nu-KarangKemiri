@extends('layouts.admin')
@section('title', 'Berita')
@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
  <form class="d-flex gap-2" method="GET"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul..."><button class="btn btn-light"><i class="bi bi-search"></i></button></form>
  <a href="{{ route('admin.berita.create') }}" class="btn btn-nu"><i class="bi bi-plus-lg"></i> Tambah Berita</a>
</div>
<div class="admin-card"><div class="table-responsive"><table class="table table-stack align-middle mb-0">
  <thead><tr><th style="width:70px">Gambar</th><th>Judul</th><th>Penulis</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  @forelse ($beritas as $b)
    <tr>
      <td>@if ($b->gambar_url)<img src="{{ $b->gambar_url }}" class="thumb-sm" alt="">@else<div class="thumb-sm d-flex align-items-center justify-content-center text-success"><i class="bi bi-image"></i></div>@endif</td>
      <td class="fw-semibold">{{ $b->judul }}</td><td data-label="Penulis">{{ $b->penulis }}</td><td class="text-nowrap" data-label="Tanggal">{{ $b->tanggal->translatedFormat('d M Y') }}</td>
      <td class="text-end text-nowrap aksi">
        <a href="{{ route('berita.show', $b->slug) }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
        <a href="{{ route('admin.berita.edit', $b) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('admin.berita.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada berita.</td></tr>@endforelse
  </tbody></table></div></div>
<div class="mt-3">{{ $beritas->links() }}</div>
@endsection
