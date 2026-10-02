<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tips = (require database_path('data/destination_tips.php'))['ijen-crater'];

        DB::table('destinations')->where('slug', 'ijen-crater')->update([
            'tips_en' => $tips['en'],
            'tips_id' => $tips['id'],
        ]);
    }

    public function down(): void
    {
        // Content update only; nothing to revert.
    }
};
