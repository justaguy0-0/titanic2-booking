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

protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'travel_time' => 'integer',
        'base_price' => 'decimal:2', // 2 знака после запятой
    ];
 public function placeDeparture()
    {
        return $this->belongsTo(PlaceDeparture::class, 'place_departure');
    }

    public function icebergArrival()
    {
        return $this->belongsTo(IcebergArrival::class, 'iceberg_arrival');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'voyages_id');
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
