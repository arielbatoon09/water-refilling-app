<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallonType extends Model
{
    protected $fillable = [
        "gallon_details",
        "gallon_size",
        "price",
        "delivery_fee",
        "flag",  
    ];
}
