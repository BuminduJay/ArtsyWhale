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
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['percent','fixed'])->default('percent');
        $table->unsignedInteger('value'); // percent or cents
        $table->unsignedInteger('min_amount_cents')->default(0);
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->unsignedInteger('max_uses')->nullable();
        $table->unsignedInteger('times_used')->default(0);
        $table->boolean('active')->default(true);
        $table->timestamps();
        $table->index(['active','expires_at']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
