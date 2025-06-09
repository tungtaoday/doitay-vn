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
            // Thêm các cột cho social login
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }
            
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('provider_id');
            }
            
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->after('email_verified_at');
            }
            
            // Thêm loyalty points columns nếu chưa có
            if (!Schema::hasColumn('users', 'loyalty_points')) {
                $table->decimal('loyalty_points', 15, 2)->default(0)->after('ban_reason');
            }
            
            if (!Schema::hasColumn('users', 'total_loyalty_earned')) {
                $table->decimal('total_loyalty_earned', 15, 2)->default(0)->after('loyalty_points');
            }
            
            if (!Schema::hasColumn('users', 'total_loyalty_redeemed')) {
                $table->decimal('total_loyalty_redeemed', 15, 2)->default(0)->after('total_loyalty_earned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToRemove = ['provider', 'provider_id', 'email_verified_at', 'password', 'loyalty_points', 'total_loyalty_earned', 'total_loyalty_redeemed'];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
