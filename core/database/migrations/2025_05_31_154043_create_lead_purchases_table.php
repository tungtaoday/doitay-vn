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
        Schema::create('lead_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Owner của company
            $table->decimal('price_paid', 10, 2); // Giá đã trả cho lead này
            $table->enum('status', ['active', 'contacted', 'quoted', 'won', 'lost'])->default('active');
            $table->text('notes')->nullable(); // Ghi chú của thợ
            $table->datetime('contacted_at')->nullable(); // Khi nào liên hệ khách hàng
            $table->datetime('quoted_at')->nullable(); // Khi nào gửi báo giá
            $table->decimal('quote_amount', 15, 2)->nullable(); // Số tiền báo giá
            $table->enum('outcome', ['pending', 'won', 'lost', 'no_response'])->default('pending');
            $table->text('outcome_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['lead_id', 'company_id']); // Mỗi company chỉ mua lead 1 lần
            $table->index(['company_id', 'status']);
            $table->index(['lead_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_purchases');
    }
};
