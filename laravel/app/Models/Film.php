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

    public function rating () {
        return $this->hasMany(Rating::class, 'id', 'film_id');
    } 
    public function categories () {
        return $this->hasMany(Rating::class, 'film_id', 'id');
    }
    public function country () {
        return $this->hasMany(Rating::class, 'film_id', 'id');
    }
    public function reviews () {
        return $this->hasMany(Raview::class, 'film_id', 'id');
    }
}
