<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type')->default('user'); // 'user', 'company'
            $table->string('type'); // 'appointment', 'campaign', 'system', 'payment', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like appointment_id, etc.
            $table->string('icon')->nullable(); // Icon class or emoji
            $table->string('color')->default('blue'); // notification color
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('priority')->default('normal'); // 'high', 'normal', 'low'
            $table->boolean('is_important')->default(false);
            $table->timestamp('expires_at')->nullable(); // Auto-delete old notifications
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'user_type']);
            $table->index(['type', 'created_at']);
            $table->index(['is_read', 'created_at']);
            $table->index('expires_at');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
}; 