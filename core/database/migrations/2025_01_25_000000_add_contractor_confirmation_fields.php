<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lead_purchases', function (Blueprint $table) {
            // Contractor self-report fields
            $table->boolean('contractor_reported')->default(false)->after('outcome_notes');
            $table->timestamp('reported_at')->nullable()->after('contractor_reported');
            $table->text('report_notes')->nullable()->after('reported_at');
            
            // Customer confirmation fields (rename from contractor_confirmed)
            $table->boolean('customer_confirmed')->default(false)->after('report_notes');
            $table->timestamp('confirmed_at')->nullable()->after('customer_confirmed');
            $table->text('confirmation_notes')->nullable()->after('confirmed_at');
        });

        Schema::table('leads', function (Blueprint $table) {
            // Track which contractor was selected
            $table->unsignedBigInteger('selected_company_id')->nullable()->after('purchased_count');
            $table->timestamp('completed_at')->nullable()->after('expires_at');
            
            $table->foreign('selected_company_id')->references('id')->on('companies')->onDelete('set null');
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