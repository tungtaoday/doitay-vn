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
            $table->integer('loyalty_points')->default(0)->after('email');
            $table->integer('total_loyalty_earned')->default(0)->after('loyalty_points');
            $table->integer('total_loyalty_redeemed')->default(0)->after('total_loyalty_earned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'total_loyalty_earned', 'total_loyalty_redeemed']);
        });
    }
};
