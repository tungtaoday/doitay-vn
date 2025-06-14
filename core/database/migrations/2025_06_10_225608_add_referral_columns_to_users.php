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
            // Add referral_code if it doesn't exist
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 50)->nullable()->unique()->after('password');
            }
            
            // Add referred_by if it doesn't exist
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->bigInteger('referred_by')->unsigned()->nullable()->after('referral_code');
            }
            
            // Add referral_count if it doesn't exist
            if (!Schema::hasColumn('users', 'referral_count')) {
                $table->integer('referral_count')->default(0)->after('referred_by');
            }
            
            // Add total_referral_earnings if it doesn't exist
            if (!Schema::hasColumn('users', 'total_referral_earnings')) {
                $table->decimal('total_referral_earnings', 28, 8)->default(0)->after('referral_count');
            }
            
            // Add country_name if it doesn't exist
            if (!Schema::hasColumn('users', 'country_name')) {
                $table->string('country_name', 80)->nullable()->after('total_referral_earnings');
            }
            
            // Add dial_code if it doesn't exist
            if (!Schema::hasColumn('users', 'dial_code')) {
                $table->string('dial_code', 10)->nullable()->after('country_name');
            }
            
            // Add provider and provider_id for social login
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider', 50)->nullable()->after('dial_code');
            }
            
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id', 100)->nullable()->after('provider');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'provider_id', 'provider', 'dial_code', 'country_name', 
                'total_referral_earnings', 'referral_count', 'referred_by', 'referral_code'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
