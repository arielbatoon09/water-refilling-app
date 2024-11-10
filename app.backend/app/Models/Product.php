<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "image_url",
        "item_name",
        "item_description",
        "item_stocks",
        "item_price",
        "flag"
    ];
}
