<?php

namespace Database\Seeders;

use App\Models\PackageItinerary;
use Illuminate\Database\Seeder;

class ItineraryPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $rules = require database_path('data/itinerary_photos.php');

        PackageItinerary::whereNull('photo')->get()->each(function (PackageItinerary $day) use ($rules) {
            $text = strtolower((string) $day->getRawOriginal('description_en'));

            foreach ($rules as $keyword => $photo) {
                if (str_contains($text, $keyword)) {
                    $day->update(['photo' => $photo]);

                    return;
                }
            }
        });
    }
}
