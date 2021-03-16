<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'address',
    ];


    // 屬於使用者的訂單
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 一筆訂單中可以有很多的商品
    public function items()
    {
        return $this->hasMany(OrderProduct::class)->join('products', 'order_products.product_id', 'products.id')->select(
            // ----------------------------order_products table
            'order_id',
            'product_id',
            'product_quantity',
            // ----------------------------products table
            'title',
            'unit_price',
            'imgUrl',
            'discount_rate',
            // 總價
            OrderProduct::raw('floor(unit_price * discount_rate) * product_quantity AS total')
        );
    }
}
