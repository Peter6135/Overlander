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
        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedTinyInteger('duration_days')->nullable()->after('capacity');
            $table->string('start_city', 100)->nullable()->after('duration_days');
            $table->string('end_city', 100)->nullable()->after('start_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['duration_days', 'start_city', 'end_city']);
        });
    }
};
