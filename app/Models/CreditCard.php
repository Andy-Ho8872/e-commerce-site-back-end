<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreditCard extends Model
{
    use HasFactory;

    // protected $hidden = ['card_number'];
    protected $appends = ['masked_card_number'];
    protected $fillable = [
        // 以下欄位可以支援大量寫入。
        'user_id',
        'card_type',
        'card_number',
        'card_holder',
        'card_expiration_date',
        'card_CVV',
    ];

    public function getMaskedCardNumberAttribute()
    {
        return Str::snake($this->card_number);
    }
}
