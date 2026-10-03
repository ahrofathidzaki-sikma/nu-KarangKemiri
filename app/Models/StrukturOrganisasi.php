<?php
namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $fillable = ['nama', 'jabatan', 'foto', 'periode', 'keterangan'];

    public function getFotoUrlAttribute(): ?string
    {
        return Upload::url($this->foto);
    }
}
