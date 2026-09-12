<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_itineraries', function (Blueprint $table) {
            $table->string('day_label_en')->nullable()->after('day_label');
            $table->string('day_label_id')->nullable()->after('day_label_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_id')->nullable()->after('description_en');
        });

        Schema::table('package_itineraries', function (Blueprint $table) {
            $table->dropColumn(['day_label', 'description']);
        });
    }

    public function down(): void
    {
        Schema::table('package_itineraries', function (Blueprint $table) {
            $table->string('day_label')->nullable();
            $table->text('description')->nullable();
            $table->dropColumn([
                'day_label_en', 'day_label_id',
                'description_en', 'description_id',
            ]);
        });
    }
};
