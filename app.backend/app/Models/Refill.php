<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    protected $fillable = [
        "user_id",
        "gallon_id",
        "delivery_type",
        "mop",
        "no_of_gallon",
        "delivery_date",
        "status",
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function GallonType(){
        return $this->belongsTo(GallonType::class, 'gallon_id');
    }
}
