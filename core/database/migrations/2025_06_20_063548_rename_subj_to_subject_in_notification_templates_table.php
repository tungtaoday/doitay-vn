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
            // Check if the column needs to be renamed
            if (Schema::hasColumn('notification_templates', 'subj') && !Schema::hasColumn('notification_templates', 'subject')) {
                $table->renameColumn('subj', 'subject');
            }
            
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('notification_templates', 'email_sent_from_name')) {
                $table->string('email_sent_from_name', 40)->nullable()->after('email_status');
            }
            
            if (!Schema::hasColumn('notification_templates', 'email_sent_from_address')) {
                $table->string('email_sent_from_address', 40)->nullable()->after('email_sent_from_name');
            }
            
            if (!Schema::hasColumn('notification_templates', 'push_title')) {
                $table->string('push_title', 255)->nullable()->after('subject');
            }
            
            if (!Schema::hasColumn('notification_templates', 'push_body')) {
                $table->text('push_body')->nullable()->after('sms_body');
            }
            
            if (!Schema::hasColumn('notification_templates', 'push_status')) {
                $table->tinyInteger('push_status')->default(0)->after('push_body');
            }
            
            if (!Schema::hasColumn('notification_templates', 'sms_sent_from')) {
                $table->string('sms_sent_from', 40)->nullable()->after('sms_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            // Reverse the column rename
            if (Schema::hasColumn('notification_templates', 'subject') && !Schema::hasColumn('notification_templates', 'subj')) {
                $table->renameColumn('subject', 'subj');
            }
            
            // Drop the added columns if they exist
            if (Schema::hasColumn('notification_templates', 'sms_sent_from')) {
                $table->dropColumn('sms_sent_from');
            }
            
            if (Schema::hasColumn('notification_templates', 'push_status')) {
                $table->dropColumn('push_status');
            }
            
            if (Schema::hasColumn('notification_templates', 'push_body')) {
                $table->dropColumn('push_body');
            }
            
            if (Schema::hasColumn('notification_templates', 'push_title')) {
                $table->dropColumn('push_title');
            }
            
            if (Schema::hasColumn('notification_templates', 'email_sent_from_address')) {
                $table->dropColumn('email_sent_from_address');
            }
            
            if (Schema::hasColumn('notification_templates', 'email_sent_from_name')) {
                $table->dropColumn('email_sent_from_name');
            }
        });
    }
};
