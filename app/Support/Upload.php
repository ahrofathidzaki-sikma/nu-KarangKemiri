<?php
namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Upload
{
    /** Simpan file baru (hapus file lama jika ada). Mengembalikan path untuk disimpan ke DB. */
    public static function simpan(?UploadedFile $file, string $folder, ?string $lama = null): ?string
    {
        if (!$file) {
            return $lama;
        }
        self::hapus($lama);
        return $file->store($folder, 'public');
    }

    public static function hapus(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
