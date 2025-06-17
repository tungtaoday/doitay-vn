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
        Schema::create('lead_visibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('priority_score', 3, 2)->default(0); // Smart score for ranking
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Exclusive access expiry
            $table->boolean('is_purchased')->default(false);
            $table->timestamps();
            
            $table->unique(['lead_id', 'company_id']);
            $table->index(['company_id', 'expires_at']);
            $table->index(['lead_id', 'priority_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_visibility');
    }
};
