<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'category_id', 'author_id', 'title_en', 'title_id', 'slug', 'excerpt_en', 'excerpt_id',
        'content_en', 'content_id', 'cover_photo', 'is_published', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected function title(): Attribute
    {
        return Attribute::get(fn () => $this->localized('title'));
    }

    protected function excerpt(): Attribute
    {
        return Attribute::get(fn () => $this->localized('excerpt'));
    }

    protected function content(): Attribute
    {
        return Attribute::get(fn () => $this->localized('content'));
    }

    private function localized(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->attributes["{$field}_{$locale}"] ?? $this->attributes["{$field}_en"] ?? null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
