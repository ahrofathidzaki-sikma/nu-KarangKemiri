@extends('layouts.public')
@section('title', 'Daftar Anggota — KTA')
@section('content')
<link href="{{ asset('css/kta.css') }}" rel="stylesheet">
<header class="page-head">
  <div class="container">
    <a href="{{ route('kta.index') }}" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali ke KTA</a>
    <h1 class="mt-2">Pendaftaran Anggota</h1>
    <p class="lead text-white-50 mb-0">Isi data berikut. Tanda <span class="text-white">*</span> wajib diisi.</p>
  </div>
</header>

<section class="section kta-page">
  <div class="container" style="max-width:760px">
    <div class="catatan mb-4"><i class="bi bi-lightbulb"></i> <strong>Sudah pernah mendaftar?</strong> Jangan daftar lagi. <a href="{{ route('kta.lupa') }}">Cari nomor anggota Anda di sini</a>.</div>

    <form method="POST" action="{{ route('kta.daftar.store') }}" enctype="multipart/form-data" class="opsi-kta" novalidate>
      @csrf

      <h2 class="h5 mb-3"><i class="bi bi-person-vcard text-success"></i> Data diri</h2>
      <div class="row g-3 mb-4">
        <div class="col-12">
          <label class="form-label" for="nama">Nama lengkap <span class="text-danger">*</span></label>
          <input id="nama" name="nama" value="{{ old('nama') }}" autocomplete="name" class="form-control @error('nama') is-invalid @enderror">
          @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label" for="jk">Jenis kelamin <span class="text-danger">*</span></label>
          <select id="jk" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="">Pilih</option>
            <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
          </select>
          @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label" for="tempat_lahir">Tempat lahir</label>
          <input id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control">
        </div>
        <div class="col-12">
          @include('partials.tanggal-lahir')
          <div class="form-text">Tanggal lahir dipakai sebagai kunci keamanan dan untuk menemukan nomor Anda bila lupa.</div>
        </div>
      </div>

      <h2 class="h5 mb-3"><i class="bi bi-telephone text-success"></i> Kontak &amp; alamat</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label" for="no_wa">Nomor WhatsApp <span class="text-muted fw-normal">(disarankan)</span></label>
          <input id="no_wa" name="no_wa" type="tel" inputmode="tel" value="{{ old('no_wa') }}" placeholder="0812 3456 7890" class="form-control @error('no_wa') is-invalid @enderror">
          @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
          <div class="form-text">Agar nomor anggota bisa dikirim ke WhatsApp Anda.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="nik">NIK <span class="text-muted fw-normal">(boleh dikosongkan)</span></label>
          <input id="nik" name="nik" inputmode="numeric" maxlength="20" value="{{ old('nik') }}" placeholder="16 angka" class="form-control @error('nik') is-invalid @enderror">
          @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
          <label class="form-label" for="alamat">Alamat</label>
          <textarea id="alamat" name="alamat" rows="2" class="form-control" placeholder="Dusun / RT / RW">{{ old('alamat') }}</textarea>
        </div>
      </div>

      <h2 class="h5 mb-3"><i class="bi bi-people text-success"></i> Keanggotaan</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label" for="organisasi">Bergabung sebagai <span class="text-danger">*</span></label>
          <select id="organisasi" name="organisasi" class="form-select @error('organisasi') is-invalid @enderror">
            <option value="nu" @selected(old('organisasi', 'nu') === 'nu')>Umum (Ranting NU)</option>
            <option value="ipnu" @selected(old('organisasi') === 'ipnu')>IPNU — pelajar/remaja putra</option>
            <option value="ippnu" @selected(old('organisasi') === 'ippnu')>IPPNU — pelajar/remaja putri</option>
          </select>
          @error('organisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
          <div class="form-text">IPNU khusus laki-laki, IPPNU khusus perempuan.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="foto">Foto <span class="text-muted fw-normal">(boleh dikosongkan)</span></label>
          <input id="foto" type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
          @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
          <div class="form-text">Foto wajah menghadap depan, maksimal 2 MB.</div>
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-nu flex-grow-1"><i class="bi bi-check2-circle"></i> Daftar &amp; buat KTA</button>
        <a href="{{ route('kta.index') }}" class="btn btn-light">Batal</a>
      </div>
    </form>
  </div>
</section>

<script>
  // IPNU hanya untuk laki-laki, IPPNU hanya untuk perempuan
  (function () {
    var jk = document.getElementById('jk'), org = document.getElementById('organisasi');
    if (!jk || !org) return;
    function sinkron() {
      Array.prototype.forEach.call(org.options, function (o) {
        var salah = (jk.value === 'L' && o.value === 'ippnu') || (jk.value === 'P' && o.value === 'ipnu');
        o.disabled = salah; o.hidden = salah;
      });
      if (org.selectedOptions[0] && org.selectedOptions[0].disabled) org.value = 'nu';
    }
    jk.addEventListener('change', sinkron); sinkron();
  })();
</script>
@endsection
