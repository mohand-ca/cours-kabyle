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
        Schema::table('lesson_sessions', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('purchases')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lesson_sessions', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
        });
    }
};
