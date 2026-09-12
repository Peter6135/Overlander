<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_destination', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            // stop order within the route, e.g. Jogja(0) > Bromo(1) > Tumpak Sewu(2) > Ijen Crater(3)
            $table->unsignedSmallInteger('order')->default(0);
            $table->primary(['package_id', 'destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_destination');
    }
};
