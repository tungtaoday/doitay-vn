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
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Pro, Premium
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Monthly price
            $table->integer('duration_days'); // 30, 90, 365
            $table->json('features'); // List of features
            $table->integer('priority_level')->default(0); // Higher = better priority
            $table->boolean('featured_badge')->default(false);
            $table->boolean('top_listing')->default(false);
            $table->integer('max_keywords')->default(5); // Keywords for priority
            $table->boolean('analytics_access')->default(false);
            $table->boolean('custom_profile')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
