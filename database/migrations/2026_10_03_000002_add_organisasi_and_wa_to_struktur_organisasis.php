<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Kolom ini sudah ada di migration create_struktur_organisasis_table (database baru/tes),
        // tetapi belum ada di database lama. Tambahkan hanya jika belum ada.
        Schema::table('struktur_organisasis', function (Blueprint $t) {
            if (!Schema::hasColumn('struktur_organisasis', 'organisasi')) {
                $t->string('organisasi', 10)->default('ipnu')->after('jabatan'); // ipnu | ippnu
            }
            if (!Schema::hasColumn('struktur_organisasis', 'nomor_wa')) {
                $t->string('nomor_wa', 20)->nullable()->after('periode');
            }
        });
    }

    public function down(): void
    {
        $kolom = array_values(array_filter(
            ['organisasi', 'nomor_wa'],
            fn ($c) => Schema::hasColumn('struktur_organisasis', $c)
        ));

        if ($kolom) {
            Schema::table('struktur_organisasis', function (Blueprint $t) use ($kolom) {
                $t->dropColumn($kolom);
            });
        }
    }
};
