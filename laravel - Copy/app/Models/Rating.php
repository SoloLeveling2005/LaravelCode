<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public function film () {
        return $this->belongsTo(Film::class, 'film_id', 'id');
    }
}
