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
            // Add profile_complete column if it doesn't exist
            if (!Schema::hasColumn('users', 'profile_complete')) {
                $table->tinyInteger('profile_complete')->default(0)->after('ward');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop profile_complete column if it exists
            if (Schema::hasColumn('users', 'profile_complete')) {
                $table->dropColumn('profile_complete');
            }
        });
    }
};
