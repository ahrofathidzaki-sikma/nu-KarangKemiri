<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('struktur_organisasis', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('jabatan');
            $t->string('foto')->nullable();
            $t->string('periode', 50)->nullable();
            $t->text('keterangan')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('struktur_organisasis'); }
};
