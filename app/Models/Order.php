<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','order_number','full_name','phone','address','city',
        'consumer_number','tran_auth_id','payment_method','payment_status',
        'subtotal','shipping','total','status','notes',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function items()      { return $this->hasMany(OrderItem::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}
