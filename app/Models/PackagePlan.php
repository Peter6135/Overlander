<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePlan extends Model
{
    protected $fillable = ['package_id', 'name', 'price', 'features', 'is_recommended'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_recommended' => 'boolean',
        ];
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
