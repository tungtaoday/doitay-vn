<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sale CTV Onboarding — Phase 3 (hoa hồng). Ref: DUC-COMMISSION-RECORD.
 * Mỗi submission hợp lệ phát sinh tối đa 1 commission `base` (unique).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ctv_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('submission_id')->constrained('tho_submissions')->cascadeOnDelete();
            $table->unsignedInteger('so_tien');
            $table->enum('loai', ['base', 'share_bonus'])->default('base');
            $table->string('tuan', 12);
            $table->timestamps();

            // BR-COMM-1: mỗi submission tối đa 1 commission mỗi loại.
            $table->unique(['submission_id', 'loai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
