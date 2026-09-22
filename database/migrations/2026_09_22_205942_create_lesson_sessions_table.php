<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('availability_slot_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_profile_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('purchase_id')->nullable(); // FK added in Phase 5
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();

            $table->index(['learner_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_sessions');
    }
};
