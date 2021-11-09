<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_quantity',
        'variation_option_values'
    ];

    protected $casts = [
        'variation_option_values' => 'array',
    ];


    // 屬於該使用者的訂單
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    // 屬於有購買該商品使用者的訂單
    public function users() 
    {
        return $this->belongsToMany(User::class);
    }
    // 購物車內的商品
    public function cartItems()
    {
        return $this->belongsToMany(Product::class);
    }
}
