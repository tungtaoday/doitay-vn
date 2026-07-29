<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Event log sản phẩm (append-only) để đo phễu BẮC ĐẨU:
 *   thợ chia sẻ hồ sơ (profile_shared) → khách liên hệ (contact_clicked).
 * Bắn từ Mini App (thợ) + web doitay.vn (khách). Không chứa PII: actor_key là
 * zalo_id / session ẩn danh. Xem bộ metric: "[CypherAI] Doitay & ThoTot Metrics".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_events', function (Blueprint $table) {
            $table->id();
            $table->string('event', 64)->index();                 // profile_shared, contact_clicked, ...
            $table->string('surface', 16)->nullable();            // tho | khach
            $table->string('channel', 16)->nullable();            // miniapp | web
            $table->unsignedBigInteger('company_id')->nullable()->index(); // hồ sơ thợ liên quan
            $table->string('actor_key', 191)->nullable()->index();// zalo_id / session ẩn danh (không PII)
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_events');
    }
};
