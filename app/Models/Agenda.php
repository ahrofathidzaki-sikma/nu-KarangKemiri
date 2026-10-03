<?php
namespace App\Models;

use App\Models\Concerns\HasSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasSlug;

    public const STATUS = ['Akan Datang', 'Selesai'];

    protected $fillable = ['judul', 'slug', 'tanggal', 'waktu', 'lokasi', 'deskripsi', 'status'];
    protected $casts = ['tanggal' => 'date'];

    /**
     * Status ditentukan otomatis dari tanggal & waktu mulai:
     * - belum dimulai          => "Akan Datang"
     * - sudah dimulai/lewat    => "Selesai"
     * Jika waktu kosong, agenda dianggap berlangsung sepanjang hari itu (selesai setelah hari berganti).
     */
    public static function hitungStatus($tanggal, ?string $waktu = null): string
    {
        $t = Carbon::parse($tanggal);
        $mulai = $waktu ? $t->copy()->setTimeFromTimeString($waktu) : $t->copy()->endOfDay();
        return $mulai->gt(now()) ? 'Akan Datang' : 'Selesai';
    }

    /** Pindahkan agenda "Akan Datang" yang sudah dimulai menjadi "Selesai". */
    public static function sinkronStatus(): void
    {
        static::where('status', 'Akan Datang')
            ->whereDate('tanggal', '<=', now()->toDateString())
            ->get()
            ->each(function (self $a) {
                $baru = self::hitungStatus($a->tanggal, $a->waktu);
                if ($baru !== $a->status) {
                    $a->update(['status' => $baru]);
                }
            });
    }

    public function dokumentasis()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function scopeAkanDatang($q)
    {
        return $q->where('status', 'Akan Datang')->orderBy('tanggal');
    }

    public function scopeSelesai($q)
    {
        return $q->where('status', 'Selesai')->orderByDesc('tanggal');
    }
}
