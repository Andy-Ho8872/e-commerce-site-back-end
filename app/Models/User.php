<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 使用 API Token

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens; // 使用 API Token

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        // 以下欄位可以支援大量寫入。
        'email',
        'password',
        'product_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 一位使用者可以有多筆訂單
    public function orders() {
        return $this->hasMany(Order::class);
    }

}
