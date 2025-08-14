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
            // Add services column for storing service information
            if (!Schema::hasColumn('companies', 'services')) {
                $table->json('services')->nullable()->after('tags');
            }
            
            // Add business_hours column for storing working hours
            if (!Schema::hasColumn('companies', 'business_hours')) {
                $table->json('business_hours')->nullable()->after('services');
            }
            
            // Add service_areas column for storing service coverage areas
            if (!Schema::hasColumn('companies', 'service_areas')) {
                $table->text('service_areas')->nullable()->after('business_hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $columnsToRemove = ['services', 'business_hours', 'service_areas'];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('companies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 