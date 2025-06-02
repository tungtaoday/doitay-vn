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
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade'); // Người giới thiệu
            $table->foreignId('referee_id')->constrained('users')->onDelete('cascade'); // Người được giới thiệu
            $table->enum('reward_type', ['signup', 'first_company', 'milestone'])->default('signup');
            $table->decimal('reward_amount', 10, 2); // Số tiền thưởng
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            $table->text('description');
            $table->json('conditions')->nullable(); // Điều kiện để nhận thưởng
            $table->datetime('paid_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['referrer_id', 'status']);
            $table->index(['referee_id', 'reward_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
    }
};
