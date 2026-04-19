<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');

            $table->string('title', 160);
            $table->text('description');

            // Location snapshot (text, not FK — matches companies.city/district/ward style)
            $table->string('city', 100);
            $table->string('district', 100)->nullable();
            $table->string('ward', 100)->nullable();
            $table->string('address', 255)->nullable();

            // Budget window (nullable — customer có thể chưa biết)
            $table->unsignedBigInteger('budget_min')->nullable();
            $table->unsignedBigInteger('budget_max')->nullable();

            // Scheduling preference
            $table->date('preferred_date')->nullable();
            $table->enum('preferred_time_slot', ['morning', 'afternoon', 'evening', 'flexible'])->nullable();

            // Contact snapshot (prefilled từ user, có thể override)
            $table->string('contact_name', 100);
            $table->string('contact_phone', 30);

            // Attachments — JSON array of paths (public disk)
            $table->json('images')->nullable();

            // Lifecycle
            $table->enum('status', ['open', 'matched', 'closed', 'expired', 'cancelled'])->default('open');
            $table->foreignId('selected_company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('selected_appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['city', 'district']);
            $table->index('expires_at');
        });

        // Link appointment → originating service request (nullable for direct bookings)
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('service_request_id')
                ->nullable()
                ->after('company_id')
                ->constrained('service_requests')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['service_request_id']);
            $table->dropColumn('service_request_id');
        });

        Schema::dropIfExists('service_requests');
    }
};
