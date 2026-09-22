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
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->foreignId('availability_pattern_id')->nullable()->constrained('availability_patterns')->nullOnDelete();
            $table->dateTime('starts_at'); // stored as UTC
            $table->dateTime('ends_at');   // stored as UTC
            $table->enum('status', ['available', 'booked', 'cancelled'])->default('available');
            $table->timestamps();

            $table->index(['teacher_profile_id', 'starts_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
