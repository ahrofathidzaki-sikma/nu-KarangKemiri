@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if ($errors->any() && !request()->routeIs('login*'))
  <div class="alert alert-danger"><strong>Periksa kembali isian Anda:</strong><ul class="mb-0 mt-1 small">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
