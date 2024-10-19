<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item_inventory extends Model
{
    protected $fillable = [
        "image_url",
        "item_name",
        "item_description",
        "item_stocks",
        "item_price",
        "status"
    ];
}
