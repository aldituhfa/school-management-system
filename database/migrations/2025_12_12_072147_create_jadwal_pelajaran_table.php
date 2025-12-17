<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            
            // Hari dan jam pelajaran
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->integer('jam_ke'); // Jam ke-1, ke-2, dst
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            
            // Keterangan tambahan
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            // Index untuk mencegah jadwal bentrok
            $table->unique(['kelas_id', 'hari', 'jam_ke'], 'unique_kelas_waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
};