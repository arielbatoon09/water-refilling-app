<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_carts extends Model
{
    protected $fillable = [
        "user_id",
        "item_id",
        "order_quantity",
        "order_total_price",
        "flag",
        "status"
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Item(){
        return $this->belongsTo(item_inventory::class, 'item_id');
    }
}
