<?php
namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $fillable = ['nama', 'jabatan', 'organisasi', 'foto', 'periode', 'nomor_wa', 'keterangan'];

    public function scopeIpnu($query)
    {
        return $query->where('organisasi', 'ipnu');
    }

    public function scopeIppnu($query)
    {
        return $query->where('organisasi', 'ippnu');
    }

    public function getOrganisasiLabelAttribute(): string
    {
        return strtoupper($this->organisasi ?? 'ipnu');
    }

    public function getWaLinkAttribute(): ?string
    {
        if (!$this->nomor_wa) {
            return null;
        }
        $wa = preg_replace('/[^0-9]/', '', $this->nomor_wa);
        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }
        return 'https://wa.me/' . $wa;
    }


    public function getFotoUrlAttribute(): ?string
    {
        return Upload::url($this->foto);
    }
}
