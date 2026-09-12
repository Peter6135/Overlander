<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            // destination: tag destinasi (Nature, Culture, Beach, Mountain, dst)
            // activity: tag aktivitas di dalam destinasi (Tracking, Tradition, Adventure)
            // package: kelompok paket wisata (One Day Tour, Sunrise, Cave & Beach, Overland)
            // article: topik artikel/blog
            $table->enum('type', ['destination', 'activity', 'package', 'article']);
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
