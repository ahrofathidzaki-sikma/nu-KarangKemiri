@php
  $namaBulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  $label = $label ?? 'Tanggal Lahir';
  $wajib = $wajib ?? true;
@endphp
<label class="form-label">{{ $label }} @if ($wajib)<span class="text-danger">*</span>@endif</label>
<div class="row g-2">
  <div class="col-3">
    <select name="tgl" class="form-select @error('tanggal_lahir') is-invalid @enderror" aria-label="Tanggal lahir">
      <option value="">Tgl</option>
      @for ($i = 1; $i <= 31; $i++)<option value="{{ $i }}" @selected((string) old('tgl') === (string) $i)>{{ $i }}</option>@endfor
    </select>
  </div>
  <div class="col-5">
    <select name="bln" class="form-select @error('tanggal_lahir') is-invalid @enderror" aria-label="Bulan lahir">
      <option value="">Bulan</option>
      @foreach ($namaBulan as $n => $b)<option value="{{ $n }}" @selected((string) old('bln') === (string) $n)>{{ $b }}</option>@endforeach
    </select>
  </div>
  <div class="col-4">
    <input name="thn" type="text" inputmode="numeric" maxlength="4" value="{{ old('thn') }}" placeholder="Tahun"
           class="form-control @error('tanggal_lahir') is-invalid @enderror" aria-label="Tahun lahir" autocomplete="off">
  </div>
</div>
@error('tanggal_lahir')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
<div class="form-text">Contoh: 17 Agustus 1965</div>
