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
        Schema::create('features', function (Blueprint $table) {
            $table->id(); // This is the primary key column (id)
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key for category
            $table->string('name', 255); // Feature name
            $table->text('description')->nullable(); // Description of the feature
            $table->tinyInteger('status')->default(1); // Status (1 for active, 0 for inactive)
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
