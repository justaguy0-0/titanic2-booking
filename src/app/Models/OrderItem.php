<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_id',
        'entertainment_id',
        'item_type',   // 'ticket' или 'entertainment'
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // === Связи ===
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function entertainment()
    {
        return $this->belongsTo(Entertainment::class);
    }

    // === Удобно: получить сам предмет ===
    public function getItemAttribute()
    {
        return $this->item_type === 'ticket' ? $this->ticket : $this->entertainment;
    }
}
