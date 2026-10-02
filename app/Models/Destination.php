<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'name', 'slug', 'location', 'latitude', 'longitude',
        'description_en', 'description_id',
        'what_to_do_en', 'what_to_do_id',
        'point_of_interest_en', 'point_of_interest_id',
        'nature_level', 'culture_level', 'heritage_level',
        'cover_photo', 'is_active', 'created_by',
    ];

    protected function description(): Attribute
    {
        return Attribute::get(fn () => $this->localized('description'));
    }

    protected function whatToDo(): Attribute
    {
        return Attribute::get(fn () => $this->localized('what_to_do'));
    }

    protected function pointOfInterest(): Attribute
    {
        return Attribute::get(fn () => $this->localized('point_of_interest'));
    }

    protected function coverPhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->cover_photo) {
                return null;
            }

            return str_starts_with($this->cover_photo, 'http') ? $this->cover_photo : asset('storage/' . $this->cover_photo);
        });
    }

    private function localized(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->attributes["{$field}_{$locale}"] ?? $this->attributes["{$field}_en"] ?? null;
    }

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_active' => 'boolean',
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'destination_category');
    }

    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class)->orderBy('order');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_destination')->withPivot('order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
