<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('hires')->default(0);
            $table->timestamps();
            
            // Tạo index cho các trường thường được query
            $table->index('company_id');
        });

        // Tạo bản ghi thống kê cho các công ty hiện có
        DB::table('companies')->orderBy('id')->chunk(100, function ($companies) {
            $now = now();
            $statistics = [];
            
            foreach ($companies as $company) {
                $statistics[] = [
                    'company_id' => $company->id,
                    'views' => 0,
                    'hires' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            if (!empty($statistics)) {
                DB::table('company_statistics')->insert($statistics);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_statistics');
    }
}; 