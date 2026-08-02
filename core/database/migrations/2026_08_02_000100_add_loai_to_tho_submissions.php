<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hai kiểu CTV nộp công:
 * - `lam_ho`: CTV dựng hộ hồ sơ tại chỗ (cần đủ thông tin + ảnh việc) → hệ sinh
 *   link để thợ bấm nhận.
 * - `da_mo`:  thợ đã TỰ mở hồ sơ trong Mini App, CTV chỉ khai nhận công (cần SĐT
 *   + ảnh bằng chứng đã gặp: ảnh chụp chung hoặc ảnh đoạn chat).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tho_submissions', function (Blueprint $table) {
            $table->enum('loai', ['lam_ho', 'da_mo'])->default('lam_ho')->after('ctv_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('tho_submissions', function (Blueprint $table) {
            $table->dropIndex(['loai']);
            $table->dropColumn('loai');
        });
    }
};
