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
        return $this->hasMany(OrderProduct::class);
    }
}
