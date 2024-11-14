<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        "user_id",
        "address",
        "municipality",
        "city",
        "postal_code",
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
