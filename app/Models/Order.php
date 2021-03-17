<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(Product::class, 'order_product')
        ->select(
            'title',
            'unit_price',
            'discount_rate',
            Order::raw('floor(unit_price * discount_rate) * product_quantity AS pivot_total')
        )
        ->withPivot(['product_quantity']);
    }
}

// 原生 SQL 語法------------------------------------------------------------------------------------
// SELECT `title`, `unit_price`, `discount_rate`, 
// floor(unit_price * discount_rate) * product_quantity AS total, 
// `order_products`.`order_id` as `pivot_order_id`, 
// `order_products`.`product_id` as `pivot_product_id`, 
// `order_products`.`product_quantity` as `pivot_product_quantity` 

// FROM `products` INNER JOIN `order_products` 
// ON `products`.`id` = `order_products`.`product_id` 
// WHERE `order_products`.`order_id` in (SELECT `id` FROM `orders` WHERE `user_id` = `user_id`)
// 原生 SQL 語法------------------------------------------------------------------------------------