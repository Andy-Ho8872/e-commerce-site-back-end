<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'product_quantity'
    ];


    // 屬於使用者的訂單
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
