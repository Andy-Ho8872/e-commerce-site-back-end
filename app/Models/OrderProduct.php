<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    // Table name
    protected $table = 'order_product';

    // mass assignment
    protected $fillable = [
        'order_id',
        'product_id',
        'product_quantity'
    ];
}
