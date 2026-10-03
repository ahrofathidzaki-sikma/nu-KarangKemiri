<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom "kabupaten" digabung ke "nama_desa" (contoh: "Desa Karangkemiri, Kab. Banyumas").
 * Data yang sudah ada ikut digabung otomatis, lalu kolom kabupaten dikosongkan.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengaturans', function (Blueprint $t) {
            $t->string('kabupaten')->nullable()->change();
        });

        foreach (DB::table('pengaturans')->get() as $r) {
            $desa = trim((string) $r->nama_desa);
            $kab = trim((string) $r->kabupaten);
            if ($kab !== '' && stripos($desa, $kab) === false) {
                $singkat = preg_replace('/^Kabupaten\s+/i', 'Kab. ', $kab);
                if (stripos($desa, $singkat) === false) {
                    $desa .= ', ' . $singkat;
                }
            }
            DB::table('pengaturans')->where('id', $r->id)->update(['nama_desa' => $desa, 'kabupaten' => null]);
        }
    }

    public function down(): void
    {
        // Data gabungan tidak dipisah kembali; kolom dibiarkan nullable.
    }
};
