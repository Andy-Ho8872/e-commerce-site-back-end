<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProductPivot extends Pivot
{
    use HasFactory;

    protected $casts = [
        'variation_option_values' => 'array',
    ];
}