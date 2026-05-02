<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_seeded')->default(0)->after('id')->index();
            $table->date('seed_batch')->nullable()->after('is_seeded');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->tinyInteger('is_seeded')->default(0)->after('id')->index();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->tinyInteger('is_seeded')->default(0)->after('id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_seeded', 'seed_batch']);
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('is_seeded');
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('is_seeded');
        });
    }
};
