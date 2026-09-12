<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'category_id', 'name', 'slug', 'description_en', 'description_id',
        'cover_photo', 'is_featured', 'is_active', 'capacity', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::get(function () {
            $locale = app()->getLocale();

            return $this->attributes["description_{$locale}"] ?? $this->attributes['description_en'] ?? null;
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'package_destination')
            ->withPivot('order')
            ->orderBy('package_destination.order');
    }

    public function plans()
    {
        return $this->hasMany(PackagePlan::class);
    }

    public function itineraries()
    {
        return $this->hasMany(PackageItinerary::class)->orderBy('order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function remainingCapacity(string $date, ?int $excludeBookingId = null): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        $booked = $this->bookings()
            ->whereDate('trip_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($excludeBookingId, fn ($q) => $q->where('id', '!=', $excludeBookingId))
            ->sum('pax');

        return max(0, $this->capacity - $booked);
    }
}
