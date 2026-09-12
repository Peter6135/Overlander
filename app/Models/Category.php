<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'icon'];

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_category');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
