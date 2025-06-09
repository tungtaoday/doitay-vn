<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Chỉ thêm những cột chưa tồn tại
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->timestamp('mobile_verified_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'image')) {
                $table->dropColumn('image');
            }
            
            if (Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->dropColumn('mobile_verified_at');
            }
        });
    }
};
