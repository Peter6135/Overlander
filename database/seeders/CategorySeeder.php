<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // tag destinasi
            ['name' => 'Nature',        'type' => 'destination', 'icon' => 'mountain'],
            ['name' => 'Culture',       'type' => 'destination', 'icon' => 'building-library'],
            ['name' => 'Beach',         'type' => 'destination', 'icon' => 'sun'],
            ['name' => 'Mountain',      'type' => 'destination', 'icon' => 'flag'],

            // kelompok paket wisata
            ['name' => 'One Day Tour',  'type' => 'package', 'icon' => 'clock'],
            ['name' => 'Sunrise',       'type' => 'package', 'icon' => 'sun'],
            ['name' => 'Cave & Beach',  'type' => 'package', 'icon' => 'map'],
            ['name' => 'Overland',      'type' => 'package', 'icon' => 'truck'],

            // topik artikel/blog
            ['name' => 'Travel Tips',   'type' => 'article', 'icon' => 'light-bulb'],
            ['name' => 'Culture Story', 'type' => 'article', 'icon' => 'book-open'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore([
                'name'       => $cat['name'],
                'slug'       => Str::slug($cat['name']),
                'type'       => $cat['type'],
                'icon'       => $cat['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
