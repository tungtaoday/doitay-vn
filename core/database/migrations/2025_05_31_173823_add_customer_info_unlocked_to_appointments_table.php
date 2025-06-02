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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('customer_info_unlocked')->default(false)->after('status');
            $table->decimal('unlock_fee_paid', 10, 2)->nullable()->after('customer_info_unlocked');
            $table->timestamp('info_unlocked_at')->nullable()->after('unlock_fee_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['customer_info_unlocked', 'unlock_fee_paid', 'info_unlocked_at']);
        });
    }
};
