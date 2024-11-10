<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    protected $fillable = [
        "user_id",
        "gallon_details",
        "delivery_type",
        "mop",
        "delivery_date",
        "t_refill_fee",
        "t_delivery_fee",
        "status",
    ];

    // public function User(){
    //     return $this->belongsTo(User::class);
    // }

    // public function GallonType(){
    //     return $this->belongsTo(GallonType::class, 'gallon_id');
    // }
}
