<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $t) {
            $t->id();
            $t->string('nama_organisasi');
            $t->string('nama_desa');
            $t->string('kabupaten');
            $t->string('alamat')->nullable();
            $t->text('deskripsi')->nullable();
            $t->string('logo')->nullable();
            $t->string('telepon', 30)->nullable();
            $t->string('email')->nullable();
            $t->string('instagram')->nullable();
            $t->string('facebook')->nullable();
            $t->string('youtube')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengaturans'); }
};
