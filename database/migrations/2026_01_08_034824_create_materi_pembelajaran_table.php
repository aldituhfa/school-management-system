<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
 // database/migrations/xxxx_create_materi_pembelajaran_table.php
public function up()
{
    Schema::create('materi_pembelajaran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran')->onDelete('cascade');
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->string('judul');
        $table->text('deskripsi')->nullable();
        $table->enum('tipe_materi', ['pdf', 'video', 'dokumen', 'presentasi', 'lainnya']);
        $table->string('file_path');
        $table->string('file_name');
        $table->integer('file_size'); // dalam bytes
        $table->timestamps();
    });
}
};
