<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sale CTV Onboarding — Phase 1.
 * Bảng hồ sơ thợ do CTV nhập (tho_submissions) + ảnh công việc (submission_images).
 * Ref: BREQ-SALE-CTV-ONBOARDING, DUC-SUBMISSION-CREATE.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tho_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ctv_id')->constrained('users')->cascadeOnDelete();
            $table->string('ten_tho');
            $table->string('nghe');
            $table->string('khu_vuc');
            $table->string('sdt_tho', 30);
            // Dedup ở tầng service (status != rejected). Index để tra nhanh; KHÔNG unique
            // để cho phép nhập lại sau khi bị từ chối. Xem BR-SUB-1.
            $table->string('sdt_normalized', 20)->index();
            $table->unsignedSmallInteger('nam_kn')->nullable();
            $table->json('bang_gia')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('ly_do_tu_choi')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->timestamps();

            $table->index(['ctv_id', 'status']);
        });

        Schema::create('submission_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('tho_submissions')->cascadeOnDelete();
            $table->string('url');
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_images');
        Schema::dropIfExists('tho_submissions');
    }
};
