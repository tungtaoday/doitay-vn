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
            // Add location columns if they don't exist
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district', 100)->nullable()->after('city');
            }
            
            if (!Schema::hasColumn('users', 'ward')) {
                $table->string('ward', 100)->nullable()->after('district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('users', 'ward')) {
                $table->dropColumn('ward');
            }
            
            if (Schema::hasColumn('users', 'district')) {
                $table->dropColumn('district');
            }
            
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};
