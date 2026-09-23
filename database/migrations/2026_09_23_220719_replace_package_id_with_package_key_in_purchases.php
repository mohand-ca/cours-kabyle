<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
            $table->string('package_key')->after('user_id');
        });

        Schema::dropIfExists('packages');
    }

    public function down(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('sessions_count');
            $table->unsignedInteger('price_cents');
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('package_key');
            $table->foreignId('package_id')->after('user_id')->constrained()->restrictOnDelete();
        });
    }
};
