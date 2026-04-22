<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'shop_name', 'name', 'email', 'password',
        'phone', 'logo', 'banner', 'description', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
