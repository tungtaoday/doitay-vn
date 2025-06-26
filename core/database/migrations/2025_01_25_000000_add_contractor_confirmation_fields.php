<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lead_purchases', function (Blueprint $table) {
            $table->boolean('contractor_confirmed')->default(false)->after('outcome_notes');
            $table->timestamp('confirmed_at')->nullable()->after('contractor_confirmed');
            $table->text('confirmation_notes')->nullable()->after('confirmed_at');
        });
    }

    public function down()
    {
        Schema::table('lead_purchases', function (Blueprint $table) {
            $table->dropColumn(['contractor_confirmed', 'confirmed_at', 'confirmation_notes']);
        });
    }
}; 