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
        Schema::table('availability_patterns', function (Blueprint $table) {
            // start_time / end_time are the teacher's LOCAL time-of-day (not UTC).
            $table->unsignedSmallInteger('slot_duration')->default(60)->after('end_time'); // minutes
            $table->unsignedSmallInteger('buffer')->default(0)->after('slot_duration');    // minutes between slots
            $table->date('starts_on')->nullable()->after('buffer');                        // first date the pattern applies
            $table->date('until')->nullable()->after('starts_on');                         // last date (set when a series is deleted)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availability_patterns', function (Blueprint $table) {
            $table->dropColumn(['slot_duration', 'buffer', 'starts_on', 'until']);
        });
    }
};
