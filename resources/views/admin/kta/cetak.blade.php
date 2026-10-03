<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KTA — {{ $anggota->nama }}</title>
  <style>
    @page { size: 85.6mm 53.98mm; margin: 0; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: #e8e8e8;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;
      padding: 24px;
      min-height: 100vh;
    }
    .toolbar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .toolbar button, .toolbar a {
      font: 600 14px/1 system-ui, sans-serif;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      color: #fff;
      background: #0d6e3f;
    }
    .toolbar a.secondary { background: #6c757d; }
    .card {
      width: 85.6mm;
      height: 53.98mm;
      background: linear-gradient(135deg, #0a5c34 0%, #0d7a45 40%, #128a52 100%);
      color: #fff;
      border-radius: 8px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 8px 24px rgba(0,0,0,.25);
      page-break-inside: avoid;
    }
    .card::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 90% 10%, rgba(255,255,255,.12) 0%, transparent 45%),
        radial-gradient(circle at 10% 90%, rgba(0,0,0,.15) 0%, transparent 40%);
      pointer-events: none;
    }
    .card-inner {
      position: relative;
      z-index: 1;
      height: 100%;
      padding: 6px 8px;
      display: flex;
      flex-direction: column;
    }
    .header {
      display: flex;
      align-items: center;
      gap: 6px;
      border-bottom: 1px solid rgba(255,255,255,.25);
      padding-bottom: 4px;
      margin-bottom: 5px;
    }
    .logo {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }
    .logo img { width: 100%; height: 100%; object-fit: cover; }
    .logo span { font-size: 10px; font-weight: 800; color: #0d6e3f; }
    .org-name { font-size: 8px; font-weight: 700; line-height: 1.2; letter-spacing: .3px; }
    .org-sub { font-size: 6.5px; opacity: .85; }
    .body {
      display: flex;
      gap: 7px;
      flex: 1;
      min-height: 0;
    }
    .photo {
      width: 28mm;
      height: 34mm;
      border-radius: 4px;
      background: rgba(255,255,255,.15);
      border: 1.5px solid rgba(255,255,255,.4);
      overflow: hidden;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      font-weight: 700;
    }
    .photo img { width: 100%; height: 100%; object-fit: cover; }
    .info { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: space-between; padding: 1px 0; }
    .label { font-size: 5.5px; opacity: .75; text-transform: uppercase; letter-spacing: .4px; }
    .value { font-size: 8px; font-weight: 600; margin-bottom: 2.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .value.name { font-size: 10px; font-weight: 700; }
    .value.no { font-family: ui-monospace, monospace; font-size: 7.5px; letter-spacing: .5px; }
    .footer {
      border-top: 1px solid rgba(255,255,255,.2);
      padding-top: 3px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 5.5px;
      opacity: .85;
    }
    .badge-org {
      background: rgba(255,255,255,.2);
      padding: 1px 5px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 6px;
    }
    @media print {
      body { background: none; padding: 0; margin: 0; display: block; }
      .toolbar { display: none !important; }
      .card { box-shadow: none; border-radius: 0; margin: 0; }
    }
  </style>
</head>
<body>
  <div class="toolbar">
    <button onclick="window.print()">Cetak / Simpan PDF</button>
    @if (!empty($publik))
      <a href="{{ route('kta.hasil', $anggota->nomor_anggota) }}" class="secondary">Kembali</a>
    @else
      <a href="{{ route('admin.kta.index') }}" class="secondary">Kembali</a>
    @endif
  </div>


  <div class="card">
    <div class="card-inner">
      <div class="header">
        <div class="logo">
          @if ($pengaturan->logo_url)
            <img src="{{ $pengaturan->logo_url }}" alt="Logo">
          @else
            <span>NU</span>
          @endif
        </div>
        <div>
          <div class="org-name">{{ $pengaturan->nama_organisasi ?: 'Nahdlatul Ulama' }}</div>
          <div class="org-sub">{{ $pengaturan->nama_desa }}</div>
        </div>
      </div>
      <div class="body">
        <div class="photo">
          @if ($anggota->foto_url)
            <img src="{{ $anggota->foto_url }}" alt="{{ $anggota->nama }}">
          @else
            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
          @endif
        </div>
        <div class="info">
          <div>
            <div class="label">Nama</div>
            <div class="value name">{{ $anggota->nama }}</div>
            <div class="label">No. Anggota</div>
            <div class="value no">{{ $anggota->nomor_anggota }}</div>
            <div class="label">Jenis Kelamin</div>
            <div class="value">{{ $anggota->jenis_kelamin_label }}</div>
            @if ($anggota->tempat_lahir || $anggota->tanggal_lahir)
              <div class="label">TTL</div>
              <div class="value">
                {{ $anggota->tempat_lahir ?: '-' }}{{ $anggota->tanggal_lahir ? ', '.$anggota->tanggal_lahir->format('d/m/Y') : '' }}
              </div>
            @endif
          </div>
          <div>
            <div class="label">Status</div>
            <div class="value">{{ $anggota->status_label }}</div>
          </div>
        </div>
      </div>
      <div class="footer">
        <span>Kartu Tanda Anggota</span>
        <span class="badge-org">{{ $anggota->organisasi_label }}</span>
      </div>
    </div>
  </div>
</body>
</html>
