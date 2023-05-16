<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function films () {
        return $this->hasManyThrough(
            Film::class,
            Category_film::class,
            'category_id', // Внешний ключ в связующей таблице category_film, указывающий на текущую модель Category
            'id', // Внешний ключ в таблице films
            'id', // Локальный ключ в текущей модели Category
            'film_id' // Локальный ключ в связующей таблице category_film, указывающий на модель Film
        );
    }
}
