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
    //
    public function products() {
        return $this->hasMany(Product::class);
    }
}
