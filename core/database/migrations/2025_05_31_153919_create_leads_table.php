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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->json('address')->nullable();
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->decimal('lead_price', 10, 2)->default(10000); // Giá mỗi lead
            $table->datetime('needed_by')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['draft', 'active', 'closed', 'expired'])->default('active');
            $table->integer('max_contractors')->default(5); // Tối đa số thợ có thể mua lead này
            $table->integer('purchased_count')->default(0); // Đã có bao nhiêu thợ mua
            $table->json('customer_info')->nullable(); // Thông tin khách hàng (name, phone, email)
            $table->json('requirements')->nullable(); // Yêu cầu chi tiết
            $table->json('attachments')->nullable(); // File đính kèm
            $table->boolean('is_premium')->default(false); // Lead cao cấp
            $table->datetime('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['category_id', 'status']);
            $table->index(['district', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
