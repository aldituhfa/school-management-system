<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal_pelaksanaan');
            $table->decimal('target_dana', 15, 2);
            $table->decimal('nominal_per_siswa', 15, 2);
            $table->json('kelas_ids'); // menyimpan array id kelas
            $table->enum('status', ['Perencanaan', 'Sedang Berlangsung', 'Selesai'])->default('Perencanaan');
            $table->decimal('terkumpul', 15, 2)->default(0);
            $table->timestamps();

            // NOTE: tidak ada foreign key ke kelas karena kita menyimpan banyak id di kelas_ids (JSON)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
