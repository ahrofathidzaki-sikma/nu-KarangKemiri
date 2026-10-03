<?php
namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function buatSlug(string $judul, ?int $abaikanId = null): string
    {
        $dasar = Str::slug($judul) ?: 'item';
        $slug = $dasar;
        $i = 2;
        while (static::where('slug', $slug)
            ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
            ->exists()) {
            $slug = $dasar . '-' . $i++;
        }
        return $slug;
    }
}
