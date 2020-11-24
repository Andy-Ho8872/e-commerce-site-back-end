<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_quantity',
        'total_price',
        'product_id',
        'user_id'
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
