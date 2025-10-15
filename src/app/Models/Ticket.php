<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
    'voyages_id', // foreign key
    'type',
    'number',
    'price',
    'status',
];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'voyages_id');
    }
}
