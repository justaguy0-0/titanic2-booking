<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'first_name',
        'last_name',
        'birth_date',
        'passport_series',
        'passport_number',
        'citizenship',
        'age',
        'discount_percent',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'discount_percent' => 'decimal:2',
    ];

    /**
     * Связь с позицией заказа
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    /**
     * Рассчитать возраст на основе даты рождения
     */
    public function calculateAge(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * Рассчитать скидку в зависимости от возраста
     */
    public function calculateDiscount(): float
    {
        $age = $this->calculateAge();

        // Дети до 12 лет получают скидку 20%
        if ($age < 12) {
            return 20.0;
        }

        return 0.0;
    }

    /**
     * Получить полное имя пассажира
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
