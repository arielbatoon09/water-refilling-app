<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Orders extends Model
{
    protected $fillable = [
        "refid",
        "user_id",
        "cart_id",
        "order_quantity",
        "total_item_price",
        "final_price",
        "mop",
        "delivery_type",
        "status",
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
