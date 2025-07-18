<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'deposit' to the transaction_type enum
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN transaction_type ENUM('welcome_bonus', 'referral_bonus', 'lead_purchase', 'admin_adjustment', 'refund', 'deposit', 'customer_info_access')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'deposit' from the transaction_type enum
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN transaction_type ENUM('welcome_bonus', 'referral_bonus', 'lead_purchase', 'admin_adjustment', 'refund', 'customer_info_access')");
    }
};
