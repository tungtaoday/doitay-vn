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
        Schema::create('rating_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rating_id');
            $table->unsignedBigInteger('feature_id');
            $table->decimal('rating', 3, 2); // Rating from 1.00 to 5.00
            
            // Foreign key constraints
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete('cascade');
            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
            
            // Indexes for performance
            $table->index(['rating_id', 'feature_id']);
            $table->index('rating');
            
            // Ensure one rating per feature per rating
            $table->unique(['rating_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_details');
    }
};
