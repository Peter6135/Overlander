<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location'); // mis. "East Java, ID"
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->text('what_to_do')->nullable(); // "What we do there?"
            $table->text('point_of_interest')->nullable(); // "Unique/Point Of Interest"
            $table->unsignedTinyInteger('nature_level')->nullable(); // skala 1-5
            $table->unsignedTinyInteger('culture_level')->nullable();
            $table->unsignedTinyInteger('heritage_level')->nullable();
            $table->string('cover_photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
