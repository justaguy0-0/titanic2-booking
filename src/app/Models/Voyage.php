<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    /** @use HasFactory<\Database\Factories\VoyageFactory> */
    use HasFactory;

    protected $fillable = [
    'name',
    'place_departure', // foreign key
    'iceberg_arrival', // foreign key
    'departure_date',
    'arrival_date',
    'travel_time',
    'base_price',
];
}
