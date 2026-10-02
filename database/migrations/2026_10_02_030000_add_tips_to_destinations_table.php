<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->text('tips_en')->nullable()->after('point_of_interest_id');
            $table->text('tips_id')->nullable()->after('tips_en');
        });

        foreach (require database_path('data/destination_tips.php') as $slug => $tips) {
            DB::table('destinations')->where('slug', $slug)->update([
                'tips_en' => $tips['en'],
                'tips_id' => $tips['id'],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['tips_en', 'tips_id']);
        });
    }
};
