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
        Schema::table('leads', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('leads', 'deleted_at')) {
                $table->softDeletes(); // Adds deleted_at column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Only drop if column exists
            if (Schema::hasColumn('leads', 'deleted_at')) {
                $table->dropSoftDeletes(); // Removes deleted_at column
            }
        });
    }
};
