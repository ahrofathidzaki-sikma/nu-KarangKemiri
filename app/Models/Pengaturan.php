<?php
namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = [
        'nama_organisasi', 'nama_desa', 'kabupaten', 'alamat', 'deskripsi', 'logo',
        'telepon', 'email', 'instagram', 'facebook', 'youtube',
    ];

    /** Satu-satunya baris pengaturan. Jika belum ada, kembalikan nilai bawaan (tidak disimpan). */
    public static function current(): self
    {
        return static::first() ?? new static([
            'nama_organisasi' => 'Ranting NU',
            'nama_desa' => 'Desa Karangkemiri',
            'kabupaten' => 'Kabupaten Banyumas',
        ]);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return Upload::url($this->logo);
    }
}
