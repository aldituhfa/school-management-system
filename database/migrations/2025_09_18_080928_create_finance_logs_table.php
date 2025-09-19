<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finance_id')->nullable()->constrained('finances')->nullOnDelete();
            $table->enum('action', ['create', 'update', 'delete', 'payment']);
            $table->decimal('before_amount', 15, 2)->default(0);
            $table->decimal('after_amount', 15, 2)->default(0);
            $table->enum('type', ['dana_bos', 'kas']);
            $table->text('meta')->nullable(); // tambahan (ex: "SPP bulan X")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_logs');
    }
};
