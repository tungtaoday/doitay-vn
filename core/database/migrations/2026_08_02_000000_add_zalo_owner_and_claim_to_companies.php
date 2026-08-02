<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Chủ sở hữu Zalo + vé nhận hồ sơ (claim) cho hồ sơ thợ.
 *
 * - `zalo_id`: định danh Zalo của thợ sở hữu hồ sơ. Nhờ nó Mini App mới khôi phục
 *   được hồ sơ khi thợ đổi máy / cài lại app (trước đây dữ liệu chỉ nằm ở localStorage).
 * - `claim_*`: CTV dựng hồ sơ hộ → sinh vé + link; thợ bấm link, Mini App đổi vé lấy
 *   quyền sở hữu. Vé dùng 1 lần, có hạn.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('zalo_id', 64)->nullable()->after('phone')->index();
            $table->string('claim_token', 64)->nullable()->after('zalo_id')->unique();
            $table->timestamp('claim_expires_at')->nullable()->after('claim_token');
            $table->timestamp('claimed_at')->nullable()->after('claim_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['claim_token']);
            $table->dropIndex(['zalo_id']);
            $table->dropColumn(['zalo_id', 'claim_token', 'claim_expires_at', 'claimed_at']);
        });
    }
};
