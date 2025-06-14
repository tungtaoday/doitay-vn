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
        Schema::table('companies', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('companies', 'admin_feedback')) {
                $table->text('admin_feedback')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Only drop if column exists
            if (Schema::hasColumn('companies', 'admin_feedback')) {
                $table->dropColumn('admin_feedback');
            }
        });
    }
};
