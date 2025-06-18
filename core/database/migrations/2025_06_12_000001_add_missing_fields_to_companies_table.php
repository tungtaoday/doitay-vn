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
            // Add phone field if not exists
            if (!Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            // Add experience field if not exists
            if (!Schema::hasColumn('companies', 'experience')) {
                $table->integer('experience')->default(0)->after('description');
            }
            
            // Add state field if not exists
            if (!Schema::hasColumn('companies', 'state')) {
                $table->string('state')->nullable()->after('ward');
            }
            
            // Add zip field if not exists
            if (!Schema::hasColumn('companies', 'zip')) {
                $table->string('zip')->nullable()->after('state');
            }
            
            // Add country field if not exists
            if (!Schema::hasColumn('companies', 'country')) {
                $table->string('country')->default('Vietnam')->after('zip');
            }
            
            // Add url field if not exists
            if (!Schema::hasColumn('companies', 'url')) {
                $table->string('url')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $fieldsToRemove = ['phone', 'experience', 'state', 'zip', 'country', 'url'];
            
            foreach ($fieldsToRemove as $field) {
                if (Schema::hasColumn('companies', $field)) {
                    $table->dropColumn($field);
                }
            }
        });
    }
}; 