<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entertainment extends Model
{
    /** @use HasFactory<\Database\Factories\EntertainmentFactory> */
    use HasFactory;

    protected $fillable = [
    'name',
    'price',
];

    protected $casts = [
            'price' => 'decimal:2',
        ];
    }
