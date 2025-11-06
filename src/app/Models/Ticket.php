<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'voyages_id',
        'cabin_type_id',
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

    public function cabinType()
    {
        return $this->belongsTo(CabinType::class, 'cabin_type_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ticket_id');
    }
}
