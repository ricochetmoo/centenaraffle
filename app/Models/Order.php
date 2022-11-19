<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'club',
        'amount',
        'paid',
        'squareId'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
