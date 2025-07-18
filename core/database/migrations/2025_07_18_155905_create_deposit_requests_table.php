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
        Schema::create('deposit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('deposit_code')->unique(); // Mã giao dịch duy nhất
            $table->decimal('amount', 15, 2); // Số tiền nạp
            $table->enum('payment_method', ['bank_transfer', 'momo', 'zalopay', 'other'])->default('bank_transfer');
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending');
            
            // Payment information
            $table->string('bank_account_name')->nullable(); // Tên tài khoản ngân hàng của user
            $table->string('bank_account_number')->nullable(); // Số tài khoản của user
            $table->string('bank_name')->nullable(); // Tên ngân hàng của user
            $table->string('transaction_reference')->nullable(); // Mã giao dịch từ ngân hàng
            $table->datetime('payment_date')->nullable(); // Ngày thanh toán
            
            // Proof of payment
            $table->string('payment_proof')->nullable(); // Ảnh chứng minh chuyển khoản
            $table->text('user_notes')->nullable(); // Ghi chú từ user
            
            // Admin processing
            $table->foreignId('processed_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('admin_notes')->nullable(); // Ghi chú từ admin
            $table->datetime('processed_at')->nullable(); // Thời gian xử lý
            $table->string('rejection_reason')->nullable(); // Lý do từ chối
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index('deposit_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_requests');
    }
};
