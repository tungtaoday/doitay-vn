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
            // Add firstname, lastname if they don't exist
            if (!Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname', 40)->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname', 40)->nullable()->after('firstname');
            }
            
            // Add username if it doesn't exist
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 40)->unique()->after('lastname');
            }
            
            // Add country_code if it doesn't exist
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 40)->nullable()->after('email');
            }
            
            // Add mobile if it doesn't exist
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile', 40)->nullable()->after('country_code');
            }
            
            // Add image if it doesn't exist  
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image', 255)->nullable()->after('password');
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1)->comment('0: banned, 1: active')->after('image');
            }
            
            // Add kyc_data if it doesn't exist
            if (!Schema::hasColumn('users', 'kyc_data')) {
                $table->text('kyc_data')->nullable()->after('status');
            }
            
            // Add ev (email verification) if it doesn't exist
            if (!Schema::hasColumn('users', 'ev')) {
                $table->tinyInteger('ev')->default(0)->comment('0: email unverified, 1: email verified')->after('kyc_data');
            }
            
            // Add sv (mobile verification) if it doesn't exist
            if (!Schema::hasColumn('users', 'sv')) {
                $table->tinyInteger('sv')->default(0)->comment('0: mobile unverified, 1: mobile verified')->after('ev');
            }
            
            // Add about if it doesn't exist
            if (!Schema::hasColumn('users', 'about')) {
                $table->text('about')->nullable()->after('sv');
            }
            
            // Add ver_code if it doesn't exist
            if (!Schema::hasColumn('users', 'ver_code')) {
                $table->string('ver_code', 40)->nullable()->comment('stores verification code')->after('profile_complete');
            }
            
            // Add ver_code_send_at if it doesn't exist
            if (!Schema::hasColumn('users', 'ver_code_send_at')) {
                $table->datetime('ver_code_send_at')->nullable()->comment('verification send time')->after('ver_code');
            }
            
            // Add ban_reason if it doesn't exist
            if (!Schema::hasColumn('users', 'ban_reason')) {
                $table->string('ban_reason', 255)->nullable()->after('ver_code_send_at');
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
                'ban_reason', 'ver_code_send_at', 'ver_code', 'about', 'sv', 'ev', 
                'kyc_data', 'status', 'image', 'mobile', 'country_code', 
                'username', 'lastname', 'firstname'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
