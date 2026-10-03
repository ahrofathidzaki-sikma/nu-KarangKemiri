@if ($url)
  <img src="{{ $url }}" alt="{{ $alt ?? '' }}" class="thumb" loading="lazy">
@else
  <div class="ph"><i class="bi bi-image"></i></div>
@endif
