<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'events';

    public $timestamps = false;

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id', 'id');
    }
    public function channels()
    {
        return $this->hasMany(Channel::class, 'event_id', 'id');
    }
    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Channel::class);
    }
}
