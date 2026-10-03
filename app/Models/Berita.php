<?php
namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasSlug;

    protected $fillable = ['judul', 'slug', 'gambar', 'tanggal', 'penulis', 'isi'];
    protected $casts = ['tanggal' => 'date'];

    public function getGambarUrlAttribute(): ?string
    {
        return Upload::url($this->gambar);
    }

    public function getRingkasanAttribute(): string
    {
        return Str::limit(strip_tags($this->isi), 140);
    }
}
