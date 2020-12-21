<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        'title',
        'description',
        'unit_price',
        'imgUrl',
        'stock_quantity',
        'available',
        'tag_id'
    ];



    // 產品的標籤
    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }

    



    // 定義資料屬性 (選擇性)
    protected $casts = [
        'available' => 'boolean',
        'unit_price' => 'decimal:2',
        'tag_id' => 'array'
    ];
}
