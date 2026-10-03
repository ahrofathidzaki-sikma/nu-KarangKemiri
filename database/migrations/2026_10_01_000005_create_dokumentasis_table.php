<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumentasis', function (Blueprint $t) {
            $t->id();
            // Jika agenda dihapus, foto tetap ada sebagai dokumentasi umum.
            $t->foreignId('agenda_id')->nullable()->constrained('agendas')->nullOnDelete();
            $t->string('judul');
            $t->string('gambar')->nullable();
            $t->text('keterangan')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dokumentasis'); }
};
