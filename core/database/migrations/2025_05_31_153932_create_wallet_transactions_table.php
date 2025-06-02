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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']); // Credit = tiền vào, Debit = tiền ra
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2); // Số dư trước giao dịch
            $table->decimal('balance_after', 15, 2); // Số dư sau giao dịch
            $table->enum('transaction_type', [
                'welcome_bonus',     // Bonus đăng ký
                'referral_bonus',    // Bonus giới thiệu
                'lead_purchase',     // Mua lead
                'admin_adjustment',  // Admin điều chỉnh
                'refund'            // Hoàn tiền
            ]);
            $table->string('description');
            $table->json('metadata')->nullable(); // Thông tin thêm (lead_id, referral_user_id, etc.)
            $table->string('reference_id')->nullable(); // ID tham chiếu (lead_purchase_id, etc.)
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('processed_by')->nullable()->constrained('admins')->onDelete('set null'); // Admin xử lý
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['company_wallet_id', 'type']);
            $table->index(['transaction_type', 'created_at']);
            $table->index('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
