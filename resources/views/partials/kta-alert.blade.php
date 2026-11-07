@if (session('success'))<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
@if (session('info'))<div class="alert alert-info"><i class="bi bi-info-circle-fill"></i> {{ session('info') }}</div>@endif
@if (session('error'))<div class="alert alert-warning"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>@endif
