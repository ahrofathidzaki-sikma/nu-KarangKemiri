<?php

namespace App\Models;

use App\Support\Upload;
use Illuminate\Database\Eloquent\Builder;
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

    /** Ubah nomor WA apa pun (0812…, +62 812…, 812…) menjadi 62812… ; null jika kosong. */
    public static function normalisasiWa(?string $nilai): ?string
    {
        $d = preg_replace('/\D+/', '', (string) $nilai);
        if ($d === '') {
            return null;
        }
        if (str_starts_with($d, '0')) {
            return '62' . substr($d, 1);
        }
        if (str_starts_with($d, '8')) {
            return '62' . $d;
        }
        return $d;
    }

    /** Rapikan ketikan nomor anggota: "nu 2026 1" => "NU-2026-0001". */
    public static function rapikanNomor(string $input): string
    {
        $t = strtoupper(trim($input));
        if (preg_match('/^NU\D*(\d{4})\D*(\d{1,6})$/', $t, $m)) {
            return sprintf('NU-%s-%04d', $m[1], (int) $m[2]);
        }
        return $t;
    }

    /** Nama dalam bentuk seragam (huruf kecil, tanpa tanda baca) untuk membandingkan. */
    public static function normalNama(?string $nama): string
    {
        $n = mb_strtolower((string) $nama);
        $n = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $n);
        return trim(preg_replace('/\s+/', ' ', $n));
    }

    /** Cari nama secara longgar: setiap kata yang diketik harus ada di dalam nama (gelar/urutan tidak masalah). */
    public function scopeCariNama(Builder $q, string $nama): Builder
    {
        foreach (preg_split('/\s+/', trim($nama)) as $kata) {
            if ($kata !== '') {
                $q->where('nama', 'like', '%' . addcslashes($kata, '%_\\') . '%');
            }
        }
        return $q;
    }

    /** No. WA tersamar untuk daftar pilihan, mis. 0812•••••6789 */
    public function getWaSamarAttribute(): ?string
    {
        $w = self::normalisasiWa($this->no_wa);
        if (!$w) {
            return null;
        }
        $lokal = '0' . substr($w, 2);
        return substr($lokal, 0, 4) . str_repeat('•', max(strlen($lokal) - 8, 3)) . substr($lokal, -4);
    }

    /** Tautan WhatsApp ke nomor anggota sendiri berisi nomor anggotanya (agar tersimpan di chat). */
    public function getSimpanWaLinkAttribute(): ?string
    {
        $w = self::normalisasiWa($this->no_wa);
        if (!$w) {
            return null;
        }
        $teks = "Nomor anggota NU saya: {$this->nomor_anggota} (a.n. {$this->nama}). Simpan pesan ini agar tidak lupa.";
        return 'https://wa.me/' . $w . '?text=' . rawurlencode($teks);
    }

    /** Untuk admin: kirim pengingat nomor anggota ke WA anggota. */
    public function getPengingatWaLinkAttribute(): ?string
    {
        $w = self::normalisasiWa($this->no_wa);
        if (!$w) {
            return null;
        }
        $teks = "Assalamualaikum {$this->nama}. Nomor anggota NU Anda: {$this->nomor_anggota}. "
            . 'Gunakan nomor ini beserta tanggal lahir untuk membuka/mencetak KTA di website. Mohon disimpan. Terima kasih.';
        return 'https://wa.me/' . $w . '?text=' . rawurlencode($teks);
    }

        /** Generate nomor anggota unik: NU-001, NU-002, ... (mudah diingat) */
    public static function generateNomor(): string
    {
        $max = 0;
        foreach (static::pluck('nomor_anggota') as $n) {
            if (preg_match('/(\d+)$/', (string) $n, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'NU-' . str_pad((string) ($max + 1), 3, '0', STR_PAD_LEFT);
    }
}
