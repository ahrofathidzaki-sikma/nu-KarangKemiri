<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin — {{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-wrap"><div class="login-card">
  <div class="text-center mb-4">
    <span class="brand-mark mx-auto mb-2" style="width:56px;height:56px"><i class="bi bi-moon-stars-fill"></i></span>
    <h1 class="h4 mb-0">Login Admin</h1>
    <div class="meta">{{ $pengaturan->nama_organisasi }} {{ $pengaturan->nama_desa }}</div>
  </div>
  @if (session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('login.attempt') }}">@csrf
    <div class="mb-3"><label class="form-label">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label">Password</label>
      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
      @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="remember" id="rem"><label for="rem" class="form-check-label small">Ingat saya</label></div>
    <button class="btn btn-nu w-100">Masuk</button>
  </form>
  <a href="{{ route('beranda') }}" class="d-block text-center small mt-3 text-decoration-none">&larr; Kembali ke website</a>
</div></div>
</body></html>
