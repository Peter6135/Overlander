<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PackageItinerary extends Model
{
    protected $fillable = ['package_id', 'day_label_en', 'day_label_id', 'description_en', 'description_id', 'photo', 'order'];

    protected function dayLabel(): Attribute
    {
        return Attribute::get(fn () => $this->localized('day_label'));
    }

    protected function description(): Attribute
    {
        return Attribute::get(fn () => $this->localized('description'));
    }

    private function localized(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->attributes["{$field}_{$locale}"] ?? $this->attributes["{$field}_en"] ?? null;
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
