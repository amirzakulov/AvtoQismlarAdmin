<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "name",
        "name_ru",
        "code",
        "product_type_id",
        "country_id",
        "brand_id",
        "measurement",
        "price",
        "sell_price",
        "discount",
        "count",
        "guarantee",
        "picture",
        "description",
        "min_count",
    ];
}
