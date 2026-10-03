@extends('layouts.admin')
@section('title', 'Agenda')
@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
  <form class="d-flex gap-2 flex-wrap" method="GET">
    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul..." style="max-width:220px">
    <select name="status" class="form-select" style="max-width:170px" onchange="this.form.submit()">
      <option value="">Semua status</option>
      @foreach (\App\Models\Agenda::STATUS as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach
    </select>
    <button class="btn btn-light"><i class="bi bi-search"></i></button>
  </form>
  <a href="{{ route('admin.agendas.create') }}" class="btn btn-nu"><i class="bi bi-plus-lg"></i> Tambah Agenda</a>
</div>
<div class="admin-card"><div class="table-responsive"><table class="table table-stack align-middle mb-0">
  <thead><tr><th>Judul</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  @forelse ($agendas as $a)
    <tr>
      <td class="fw-semibold">{{ $a->judul }}</td>
      <td class="text-nowrap" data-label="Tanggal">{{ $a->tanggal->translatedFormat('d M Y') }}<div class="meta">{{ $a->waktu }}</div></td>
      <td data-label="Lokasi">{{ $a->lokasi }}</td>
      <td data-label="Status"><span class="badge-status {{ $a->status === 'Selesai' ? 'badge-done' : 'badge-soon' }}">{{ $a->status }}</span></td>
      <td class="text-end text-nowrap aksi">
        <a href="{{ route('admin.dokumentasi.create', ['agenda' => $a->id]) }}" class="btn btn-sm btn-light" title="Tambah dokumentasi"><i class="bi bi-image"></i></a>
        <a href="{{ route('admin.agendas.edit', $a) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('admin.agendas.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus agenda ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada agenda.</td></tr>@endforelse
  </tbody></table></div></div>
<div class="mt-3">{{ $agendas->links() }}</div>
@endsection
