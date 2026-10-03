@extends('layouts.admin')
@section('title', 'Anggota & KTA')
@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <div class="d-flex flex-wrap gap-2">
    <span class="badge bg-success">Aktif: {{ $totalAktif }}</span>
    <span class="badge bg-secondary">Nonaktif/Undur: {{ $totalNonaktif }}</span>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('admin.kta.unduh', request()->only('status')) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Unduh CSV</a>
    <a href="{{ route('admin.kta.cetak-massal') }}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak Semua KTA Aktif</a>
    <a href="{{ route('admin.kta.create') }}" class="btn btn-nu btn-sm"><i class="bi bi-plus-lg"></i> Tambah Anggota</a>
  </div>
</div>

<form method="GET" class="admin-card p-3 mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label small mb-1">Cari</label>
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nama / No. Anggota / NIK">
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">Semua</option>
        <option value="aktif" @selected(request('status')==='aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status')==='nonaktif')>Nonaktif/Undur</option>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">Organisasi</label>
      <select name="organisasi" class="form-select form-select-sm">
        <option value="">Semua</option>
        <option value="nu" @selected(request('organisasi')==='nu')>NU</option>
        <option value="ipnu" @selected(request('organisasi')==='ipnu')>IPNU</option>
        <option value="ippnu" @selected(request('organisasi')==='ippnu')>IPPNU</option>
      </select>
    </div>
    <div class="col-md-4 d-flex gap-2">
      <button class="btn btn-nu btn-sm">Filter</button>
      <a href="{{ route('admin.kta.index') }}" class="btn btn-light btn-sm">Reset</a>
    </div>
  </div>
</form>

<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-stack align-middle mb-0">
      <thead>
        <tr>
          <th style="width:56px">Foto</th>
          <th>No. Anggota</th>
          <th>Nama</th>
          <th>Organisasi</th>
          <th>Status</th>
          <th>No. WA</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($anggotas as $a)
          <tr>
            <td>
              @if ($a->foto_url)
                <img src="{{ $a->foto_url }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover" alt="">
              @else
                <div class="avatar m-0" style="width:40px;height:40px;font-size:1rem;border-width:2px">{{ strtoupper(substr($a->nama,0,1)) }}</div>
              @endif
            </td>
            <td class="small font-monospace">{{ $a->nomor_anggota }}</td>
            <td class="fw-semibold">{{ $a->nama }}</td>
            <td><span class="badge bg-light text-dark border">{{ $a->organisasi_label }}</span></td>
            <td>
              @if ($a->status === 'aktif')
                <span class="badge bg-success">Aktif</span>
              @else
                <span class="badge bg-secondary" title="{{ $a->alasan_undur }}">Undur</span>
              @endif
            </td>
            <td class="small">{{ $a->no_wa ?: '-' }}</td>
            <td class="text-end text-nowrap aksi">
              <a href="{{ route('admin.kta.cetak', $a) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Cetak KTA"><i class="bi bi-card-heading"></i></a>
              <a href="{{ route('admin.kta.edit', $a) }}" class="btn btn-sm btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('admin.kta.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus anggota ini?')">@csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data anggota.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $anggotas->links() }}</div>
<p class="meta mt-2">KTA (Kartu Tanda Anggota) dapat dicetak per orang atau massal. Gunakan status <strong>Nonaktif/Undur</strong> bila anggota mengundurkan diri.</p>
@endsection
