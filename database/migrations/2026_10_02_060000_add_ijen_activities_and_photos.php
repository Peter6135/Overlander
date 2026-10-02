<?php

use App\Models\Activity;
use App\Models\Destination;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $data = require database_path('data/ijen_activities.php');

        $ijen = Destination::where('slug', 'ijen-crater')->first();

        if (! $ijen) {
            return;
        }

        Activity::where('destination_id', $ijen->id)
            ->where('title', 'Blue Fire Trekking')
            ->update(['photo' => $data['blue_fire_photo']]);

        foreach ($data['new'] as $a) {
            Activity::updateOrCreate(
                ['destination_id' => $ijen->id, 'title' => $a['title']],
                ['description_en' => $a['desc_en'], 'description_id' => $a['desc_id'], 'type' => $a['type'], 'photo' => $a['photo']]
            );
        }
    }

    public function down(): void
    {
        // Content update only; nothing to revert.
    }
};
