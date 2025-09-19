<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['dana_bos', 'kas']);
            $table->string('category'); // spp, event, donasi, investor, gaji, listrik, dll
            $table->decimal('amount', 15, 2);
            $table->enum('in_out', ['in', 'out']); // pemasukan / pengeluaran
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // siapa input/ubah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
