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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->string('transaction_type'); // earned, redeemed
            $table->string('source'); // appointment_completed, referral, review, etc
            $table->text('description')->nullable();
            $table->foreignId('related_id')->nullable(); // appointment_id, referral_id, etc
            $table->string('related_type')->nullable(); // appointment, referral, etc
            $table->decimal('amount', 10, 2)->default(0); // VND value equivalent
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'transaction_type']);
            $table->index(['user_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
