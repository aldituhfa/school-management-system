<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name'); // nama guru/staff
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete(); // who created entry
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->timestamp('pay_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
