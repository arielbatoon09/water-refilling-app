<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallonType extends Model
{
    protected $fillable = [
        "gallon_size",
        "gallon_price",
        "gallon_image",
        "delivery_fee",
        "flag",  
    ];
}
