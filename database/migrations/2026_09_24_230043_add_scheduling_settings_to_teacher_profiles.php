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
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->unsignedSmallInteger('default_slot_duration')->default(60)->after('status'); // minutes
            $table->unsignedSmallInteger('slot_buffer')->default(0)->after('default_slot_duration'); // minutes
            $table->unsignedSmallInteger('booking_horizon_weeks')->default(8)->after('slot_buffer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn(['default_slot_duration', 'slot_buffer', 'booking_horizon_weeks']);
        });
    }
};
