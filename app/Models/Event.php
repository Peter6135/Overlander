<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title_en', 'title_id', 'description_en', 'description_id',
        'cover_photo', 'event_date', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'event_date' => 'date',
        ];
    }

    protected function title(): Attribute
    {
        return Attribute::get(fn () => $this->localized('title'));
    }

    protected function description(): Attribute
    {
        return Attribute::get(fn () => $this->localized('description'));
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
