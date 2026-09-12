<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // login required to book (spec: non-members can only view)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // nullable: supports a "custom booking" not tied to a standard package
            $table->foreignId('package_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('package_plan_id')->nullable()->constrained('package_plans')->nullOnDelete();
            $table->boolean('is_custom')->default(false);
            $table->text('custom_request')->nullable(); // request details when is_custom
            $table->string('guest_name'); // contact for this trip, prefilled from profile but editable
            $table->string('guest_email');
            $table->string('guest_phone', 20);
            $table->date('trip_date');
            $table->text('message')->nullable();
            $table->decimal('total_price', 12, 2)->nullable(); // null when custom and no price quote yet
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_method', ['paypal', 'whatsapp'])->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
