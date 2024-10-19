<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_checkout extends Model
{
    protected $fillable = [
        "user_id",
        "cart_id",
        "total_price",
        "shipping_address",
        "billing_address",
        "mop",
        "payment_status",
        "order_status"
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Cart(){
        return $this->belongsTo(order_carts::class, 'cart_id');
    }
}
