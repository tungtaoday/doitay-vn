<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Theo dõi chi trả hoa hồng CTV (P2.1 vận hành): paid_at NULL = chưa trả.
 * Đối soát tuần: đánh dấu đã trả theo CTV trên /quan-tri.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->index()->after('tuan');
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
};
