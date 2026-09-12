<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rincian hari-per-hari buat paket multi-hari (mis. Overland), sesuai
        // wireframe "Day 1-2 Description", "Day 3-4 Description", dst.
        Schema::create('package_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('day_label'); // mis. "Day 1-2"
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_itineraries');
    }
};
