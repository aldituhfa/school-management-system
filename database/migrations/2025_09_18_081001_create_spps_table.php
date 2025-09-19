<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spps', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_identifier')->nullable(); // NIS atau identitas (opsional)
            $table->unsignedTinyInteger('month'); // 1..12
            $table->year('year');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->foreignId('tu_id')->nullable()->constrained('users')->nullOnDelete(); // yang input / memproses bayar
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['student_identifier','month','year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spps');
    }
};
