<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array',
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
