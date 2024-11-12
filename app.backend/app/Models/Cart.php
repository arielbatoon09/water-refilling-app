<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        "user_id",
        "product_id",
        "order_quantity",
        "unit_price",
        "flag",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}