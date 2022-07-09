<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    use Traits\SerializeDate; //* 修正時間格式
    
    protected $fillable = [ 
        'title',
        'description',
        'unit_price',
        'imgUrl',
        'stock_quantity',
        'available',
        'discount_rate',
        'rating'
    ];

    protected $casts = [
        'unit_price' => 'integer',
    ];

    // 產品的標籤
    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }
    // 產品的規格
    public function variations() 
    {
        return $this->hasMany(Variation::class);
    }
}
