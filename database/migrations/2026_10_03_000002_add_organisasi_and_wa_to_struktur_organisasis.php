<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('struktur_organisasis', function (Blueprint $t) {
            $t->string('organisasi', 10)->default('ipnu')->after('jabatan'); // ipnu | ippnu
            $t->string('nomor_wa', 20)->nullable()->after('periode');
        });
    }

    public function down(): void
    {
        Schema::table('struktur_organisasis', function (Blueprint $t) {
            $t->dropColumn(['organisasi', 'nomor_wa']);
        });
    }
};
