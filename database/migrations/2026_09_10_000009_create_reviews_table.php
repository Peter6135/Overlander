<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // nullable: a review can be about a specific destination, or a general company testimonial (homepage)
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            // nullable: when set, this review is verified from a guest who actually booked
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reviewer_name'); // stays stored even if user_id is null/deleted
            $table->string('reviewer_avatar')->nullable();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_featured')->default(false); // shown in "What They Say About Us"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
