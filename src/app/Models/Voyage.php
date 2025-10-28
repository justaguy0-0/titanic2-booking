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
        'departure_place_id',
        'arrival_place_id',
        'departure_date',
        'arrival_date',
        'travel_time',
        'base_price',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'travel_time' => 'integer',
        'base_price' => 'decimal:2',
    ];

    // Новые отношения
    public function departurePlace()
    {
        return $this->belongsTo(Place::class, 'departure_place_id');
    }

    public function arrivalPlace()
    {
        return $this->belongsTo(Place::class, 'arrival_place_id');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format((float)$this->base_price, 2, ',', ' ') . ' ₽';
    }

    /**
     * Accessor: Получить длительность путешествия в днях
     * Использование: $voyage->duration_days
     */
    public function getDurationDaysAttribute()
    {
        if ($this->departure_date && $this->arrival_date) {
            return $this->departure_date->diffInDays($this->arrival_date);
        }
        return 0;
    }
}
