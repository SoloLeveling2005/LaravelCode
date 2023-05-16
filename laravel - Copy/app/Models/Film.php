<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rating;
use App\Models\Raview;

class Film extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ratings () {
        return $this->hasMany(Rating::class, 'id', 'film_id');
    }
    public function categories () {
        return $this->hasManyThrough(Category::class, Category_film::class, 'film_id', 'id', 'id', 'category_id');
    }
    public function country () {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function reviews () {
        return $this->hasMany(Review::class, 'film_id', 'id');
    }
}
