<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('title_id')->nullable()->after('title');
        });

        foreach (require database_path('data/activity_titles_id.php') as $en => $id) {
            DB::table('activities')->where('title', $en)->update(['title_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('title_id');
        });
    }
};
