<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'category_id',
        'count',
        'price',
        'sell_price',
        'discount',
        'dollar_rate',
    ];
}
