<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_id')->nullable()->after('description_en');
            $table->text('what_to_do_en')->nullable()->after('what_to_do');
            $table->text('what_to_do_id')->nullable()->after('what_to_do_en');
            $table->text('point_of_interest_en')->nullable()->after('point_of_interest');
            $table->text('point_of_interest_id')->nullable()->after('point_of_interest_en');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['description', 'what_to_do', 'point_of_interest']);
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->text('what_to_do')->nullable();
            $table->text('point_of_interest')->nullable();
            $table->dropColumn([
                'description_en', 'description_id',
                'what_to_do_en', 'what_to_do_id',
                'point_of_interest_en', 'point_of_interest_id',
            ]);
        });
    }
};
