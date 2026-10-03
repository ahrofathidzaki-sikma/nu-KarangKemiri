<?php

namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $fillable = [
        'nomor_anggota', 'nama', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat', 'no_wa', 'foto', 'organisasi', 'status', 'tanggal_bergabung',
        'tanggal_undur', 'alasan_undur', 'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
        'tanggal_undur' => 'date',
    ];

    public function scopeAktif($q)
    {
        return $q->where('status', 'aktif');
    }

    public function scopeNonaktif($q)
    {
        return $q->where('status', 'nonaktif');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return Upload::url($this->foto);
    }

    public function getOrganisasiLabelAttribute(): string
    {
        return match ($this->organisasi) {
            'ipnu' => 'IPNU',
            'ippnu' => 'IPPNU',
            default => 'NU',
        };
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif / Undur';
    }

    public function getWaLinkAttribute(): ?string
    {
        if (!$this->no_wa) {
            return null;
        }
        $wa = preg_replace('/[^0-9]/', '', $this->no_wa);
        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }
        return 'https://wa.me/' . $wa;
    }

    /** Generate nomor anggota unik: NU-{KODE}-{YYYY}-{XXXX} */
    public static function generateNomor(): string
    {
        $tahun = date('Y');
        $prefix = 'NU-' . $tahun . '-';
        $last = static::where('nomor_anggota', 'like', $prefix . '%')
            ->orderByDesc('nomor_anggota')
            ->value('nomor_anggota');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
