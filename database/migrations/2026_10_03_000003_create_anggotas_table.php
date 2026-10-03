<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $t) {
            $t->id();
            $t->string('nomor_anggota', 30)->unique(); // auto: NU-DESA-YYYY-XXXX
            $t->string('nama');
            $t->string('nik', 20)->nullable();
            $t->enum('jenis_kelamin', ['L', 'P']);
            $t->string('tempat_lahir', 100)->nullable();
            $t->date('tanggal_lahir')->nullable();
            $t->text('alamat')->nullable();
            $t->string('no_wa', 20)->nullable();
            $t->string('foto')->nullable();
            $t->string('organisasi', 10)->default('nu'); // nu | ipnu | ippnu
            $t->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $t->date('tanggal_bergabung')->nullable();
            $t->date('tanggal_undur')->nullable();
            $t->string('alasan_undur')->nullable();
            $t->text('keterangan')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
