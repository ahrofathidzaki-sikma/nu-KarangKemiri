@extends('layouts.admin')
@section('title', 'Dokumentasi')
@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.dokumentasi.create') }}" class="btn btn-nu"><i class="bi bi-plus-lg"></i> Tambah Dokumentasi</a></div>
<div class="admin-card"><div class="table-responsive"><table class="table table-stack align-middle mb-0">
  <thead><tr><th style="width:80px">Foto</th><th>Judul</th><th>Agenda</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  @forelse ($dokumentasis as $d)
    <tr>
      <td>@if ($d->gambar_url)<img src="{{ $d->gambar_url }}" class="thumb-sm" alt="">@else<div class="thumb-sm d-flex align-items-center justify-content-center text-success"><i class="bi bi-camera"></i></div>@endif</td>
      <td class="fw-semibold">{{ $d->judul }}<div class="meta">{{ \Illuminate\Support\Str::limit($d->keterangan, 60) }}</div></td>
      <td data-label="Agenda">{{ $d->agenda?->judul ?? '— (umum)' }}</td>
      <td class="text-end text-nowrap aksi">
        <a href="{{ route('admin.dokumentasi.edit', $d) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('admin.dokumentasi.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Hapus dokumentasi ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
  @empty<tr><td colspan="4" class="text-center text-muted py-4">Belum ada dokumentasi.</td></tr>@endforelse
  </tbody></table></div></div>
<div class="mt-3">{{ $dokumentasis->links() }}</div>
@endsection
