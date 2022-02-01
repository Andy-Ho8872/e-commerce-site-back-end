<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        // 以下欄位可以支援大量寫入。
        'product_id',
        'title',
        'options',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
