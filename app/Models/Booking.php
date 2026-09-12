<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'package_plan_id', 'is_custom', 'custom_request',
        'guest_name', 'guest_email', 'guest_phone',
        'trip_date', 'pax', 'message', 'total_price', 'status',
        'payment_method', 'payment_scheme', 'deposit_amount', 'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'trip_date' => 'date',
            'pax' => 'integer',
            'total_price' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'is_custom' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function packagePlan()
    {
        return $this->belongsTo(PackagePlan::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Per spec: cancel/edit allowed only up to 14 days before the trip date
    public function canModify(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && Carbon::today()->diffInDays($this->trip_date, false) >= 14;
    }
}
