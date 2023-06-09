<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Gender;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    public function gender() {
        return $this->belongsTo(Gender::class, 'gender_id', 'id');
    }

    public function reviewCount() {
        return count($this->hasMany(Review::class, 'user_id', 'id'));
    }

    public function ratingCount() {
        return count($this->hasMany(Rating::class, 'user_id', 'id'));
    }



}
