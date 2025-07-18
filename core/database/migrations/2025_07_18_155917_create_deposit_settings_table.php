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
        Schema::create('deposit_settings', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method'); // bank_transfer, momo, zalopay, etc.
            $table->string('name'); // Tên hiển thị (VD: "Ngân hàng Vietcombank", "Ví MoMo")
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // QR Code
            $table->string('qr_code_image')->nullable(); // Đường dẫn đến file QR
            
            // Bank Transfer Info
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('swift_code')->nullable();
            
            // E-wallet Info  
            $table->string('wallet_phone')->nullable(); // Số điện thoại ví điện tử
            $table->string('wallet_name')->nullable(); // Tên chủ ví
            
            // Instructions
            $table->text('instructions')->nullable(); // Hướng dẫn chuyển khoản
            $table->text('note_template')->nullable(); // Template ghi chú (VD: "NAP [USER_ID] [AMOUNT]")
            
            // Limits
            $table->decimal('min_amount', 15, 2)->default(10000); // Số tiền nạp tối thiểu
            $table->decimal('max_amount', 15, 2)->default(50000000); // Số tiền nạp tối đa
            $table->integer('processing_hours')->default(24); // Thời gian xử lý (giờ)
            
            $table->timestamps();
            
            // Indexes
            $table->index(['payment_method', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_settings');
    }
};
