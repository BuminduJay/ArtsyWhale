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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('status')->default('pending'); // pending|paid|shipped|cancelled|refunded
        $table->unsignedInteger('amount_cents');
        $table->string('currency', 3)->default('usd');

        // Stripe (Option A):
        $table->string('stripe_checkout_session_id')->nullable();
        $table->string('stripe_payment_intent_id')->nullable();
        $table->string('payment_status')->default('pending'); // pending|paid|failed|refunded

        // Address snapshots (denormalized for history)
        $table->json('shipping_address')->nullable();
        $table->json('billing_address')->nullable();

        $table->timestamps();
        $table->index(['user_id','status']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
