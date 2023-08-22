<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_Addresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    // Many-to-One relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
