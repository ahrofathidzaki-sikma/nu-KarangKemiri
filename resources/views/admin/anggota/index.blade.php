@extends('layouts.admin')
@section('title', 'Struktur Organisasi')
@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.anggota.create') }}" class="btn btn-nu"><i class="bi bi-plus-lg"></i> Tambah Pengurus</a></div>
<div class="admin-card"><div class="table-responsive"><table class="table table-stack align-middle mb-0">
  <thead><tr><th style="width:70px">Foto</th><th>Nama</th><th>Jabatan</th><th>Periode</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  @forelse ($pengurus as $p)
    <tr>
      <td>@if ($p->foto_url)<img src="{{ $p->foto_url }}" class="thumb-sm rounded-circle" style="width:44px;height:44px" alt="">@else<div class="avatar m-0" style="width:44px;height:44px;font-size:1.1rem;border-width:2px">{{ strtoupper(substr($p->nama,0,1)) }}</div>@endif</td>
      <td class="fw-semibold">{{ $p->nama }}</td><td data-label="Jabatan">{{ $p->jabatan }}</td><td data-label="Periode">{{ $p->periode ?: '-' }}</td>
      <td class="text-end text-nowrap aksi">
        <a href="{{ route('admin.anggota.edit', $p) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('admin.anggota.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Hapus pengurus ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengurus.</td></tr>@endforelse
  </tbody></table></div></div>
<div class="mt-3">{{ $pengurus->links() }}</div>
<p class="meta mt-2">Urutan tampil di website mengikuti urutan input (data lebih awal tampil lebih dulu).</p>
@endsection
