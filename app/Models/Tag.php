<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    //
    public $incrementing = false;
    protected $keyType = 'string';

    // 標籤在很多產品上
    public function product() 
    {
        return $this->hasMany(Product::class);
    }
}
