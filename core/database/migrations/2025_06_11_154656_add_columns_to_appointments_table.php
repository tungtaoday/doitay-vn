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
        Schema::table('appointments', function (Blueprint $table) {
            // Check and add columns that don't exist
            if (!Schema::hasColumn('appointments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'recipient_name')) {
                $table->string('recipient_name');
            }
            if (!Schema::hasColumn('appointments', 'recipient_phone')) {
                $table->string('recipient_phone');
            }
            if (!Schema::hasColumn('appointments', 'recipient_address')) {
                $table->text('recipient_address');
            }
            if (!Schema::hasColumn('appointments', 'appointment_date')) {
                $table->date('appointment_date');
            }
            if (!Schema::hasColumn('appointments', 'appointment_time')) {
                $table->time('appointment_time');
            }
            if (!Schema::hasColumn('appointments', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $columns = [
                'user_id', 'company_id', 'recipient_name', 'recipient_phone', 
                'recipient_address', 'appointment_date', 'appointment_time', 
                'notes', 'status'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('appointments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
