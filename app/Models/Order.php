<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Many-to-One relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One-to-Many relation with OrderDetails
    public function orderDetails()
    {
        return $this->hasMany(Order_Details::class);
    }
}
