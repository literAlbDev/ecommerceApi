<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_Addresses extends Model
{
    use HasFactory;

    // Many-to-One relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
