<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jam_belajars', function (Blueprint $table) {
            $table->time('waktu_istirahat2_mulai')->nullable()->after('waktu_istirahat_selesai');
            $table->time('waktu_istirahat2_selesai')->nullable()->after('waktu_istirahat2_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('jam_belajars', function (Blueprint $table) {
            $table->dropColumn('waktu_istirahat2_mulai');
            $table->dropColumn('waktu_istirahat2_selesai');
        });
    }
};
