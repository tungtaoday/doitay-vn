<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lead_purchases', function (Blueprint $table) {
            // Contractor self-report fields - check if columns exist first
            if (!Schema::hasColumn('lead_purchases', 'contractor_reported')) {
                $table->boolean('contractor_reported')->default(false)->after('outcome_notes');
            }
            if (!Schema::hasColumn('lead_purchases', 'reported_at')) {
                $table->timestamp('reported_at')->nullable()->after('contractor_reported');
            }
            if (!Schema::hasColumn('lead_purchases', 'report_notes')) {
                $table->text('report_notes')->nullable()->after('reported_at');
            }
            
            // Customer confirmation fields
            if (!Schema::hasColumn('lead_purchases', 'customer_confirmed')) {
                $table->boolean('customer_confirmed')->default(false)->after('report_notes');
            }
            if (!Schema::hasColumn('lead_purchases', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('customer_confirmed');
            }
            if (!Schema::hasColumn('lead_purchases', 'confirmation_notes')) {
                $table->text('confirmation_notes')->nullable()->after('confirmed_at');
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            // Track which contractor was selected
            if (!Schema::hasColumn('leads', 'selected_company_id')) {
                $table->unsignedBigInteger('selected_company_id')->nullable()->after('purchased_count');
                $table->foreign('selected_company_id')->references('id')->on('companies')->onDelete('set null');
            }
            if (!Schema::hasColumn('leads', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('expires_at');
            }
        });
    }

    public function down()
    {
        Schema::table('lead_purchases', function (Blueprint $table) {
            $table->dropColumn([
                'contractor_reported', 
                'reported_at', 
                'report_notes',
                'customer_confirmed', 
                'confirmed_at', 
                'confirmation_notes'
            ]);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['selected_company_id']);
            $table->dropColumn(['selected_company_id', 'completed_at']);
        });
    }
}; 