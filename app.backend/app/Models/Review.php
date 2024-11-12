<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $fillable = [
        "user_id",
        "resource",
        "resource_ref",
        "details",
        "rate",
        "comment",
    ];
}
