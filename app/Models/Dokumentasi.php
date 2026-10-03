<?php
namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $fillable = ['agenda_id', 'judul', 'gambar', 'keterangan'];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function getGambarUrlAttribute(): ?string
    {
        return Upload::url($this->gambar);
    }
}
