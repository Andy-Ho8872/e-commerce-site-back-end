<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use Traits\SerializeDate; //* 修正時間格式
    //
    public $incrementing = false;
    protected $keyType = 'string';


    // 標籤在很多產品上
    public function products() 
    {
        return $this->belongsToMany(Product::class);
    } 
}
