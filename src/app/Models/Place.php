<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function scopeDeparture($query)
    {
        return $query->where('type', 'departure');
    }

    public function scopeArrival($query)
    {
        return $query->where('type', 'arrival');
    }

    public function departureVoyages()
    {
        return $this->hasMany(Voyage::class, 'departure_place_id');
    }

    public function arrivalVoyages()
    {
        return $this->hasMany(Voyage::class, 'arrival_place_id');
    }
}
