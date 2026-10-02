<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['destination_id', 'title', 'description_en', 'description_id', 'type', 'photo'];

    protected function description(): Attribute
    {
        return Attribute::get(function () {
            $locale = app()->getLocale();

            return $this->attributes["description_{$locale}"] ?? $this->attributes['description_en'] ?? null;
        });
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->photo) {
                return null;
            }

            return str_starts_with($this->photo, 'http') ? $this->photo : asset('storage/' . $this->photo);
        });
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
