<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tracking KÍCH HOẠT thợ (KPI trung tâm — sale-service-model.md):
 *  - appointments.confirmed_at: thời điểm thợ xác nhận (min theo company = mốc kích hoạt).
 *  - commissions.loai thêm 'activation' cho thưởng kích hoạt CTV.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->index()->after('status');
        });

        DB::statement("ALTER TABLE commissions MODIFY loai ENUM('base','share_bonus','activation') NOT NULL DEFAULT 'base'");
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('confirmed_at');
        });

        DB::statement("ALTER TABLE commissions MODIFY loai ENUM('base','share_bonus') NOT NULL DEFAULT 'base'");
    }
};
