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
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->enum('flow_type', ['auto', 'marketing', 'system'])->default('system')->after('act');
            $table->text('flow_description')->nullable()->after('flow_type');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal')->after('flow_description'); 
            $table->boolean('is_scheduled')->default(false)->after('priority');
            $table->timestamp('scheduled_at')->nullable()->after('is_scheduled');
            $table->json('recipient_criteria')->nullable()->after('scheduled_at');
            $table->integer('sent_count')->default(0)->after('recipient_criteria');
            $table->timestamp('last_sent_at')->nullable()->after('sent_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropColumn([
                'flow_type', 
                'flow_description', 
                'priority',
                'is_scheduled',
                'scheduled_at',
                'recipient_criteria',
                'sent_count',
                'last_sent_at'
            ]);
        });
    }
}; 