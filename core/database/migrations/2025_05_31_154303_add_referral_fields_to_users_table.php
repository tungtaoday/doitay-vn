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
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 10)->unique()->nullable(); // Mã giới thiệu của user này
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null'); // Được giới thiệu bởi ai
            $table->integer('referral_count')->default(0); // Đã giới thiệu bao nhiêu người
            $table->decimal('total_referral_earnings', 10, 2)->default(0); // Tổng thu nhập từ referral
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'referral_count', 'total_referral_earnings']);
        });
    }
};
