<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    // 支援一次性大量寫入
    protected $fillable = [ 
        'title',
        'description',
        'unit_price',
        'imgUrl',
        'stock_quantity',
        'available',
        'tag_id'
    ];

    // 定義資料關聯性 (產品屬於某一類的標籤)
    public function tag() 
    {
        return $this->belongsTo(Tag::class);
    }


    // 定義資料屬性 (選擇性)
    protected $casts = [
        'available' => 'boolean',
        'unit_price' => 'decimal:2',
        // 'tag_id' => 'array'
    ];
}
