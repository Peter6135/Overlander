<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            // category = kelompok paket, mis. One Day Tour, Sunrise, Cave & Beach, Overland
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name'); // mis. "Temple Heritage"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_photo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
