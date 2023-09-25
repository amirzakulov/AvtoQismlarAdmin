<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "product_id",
        "price",
        "count",
        "dollar_rate",
        "set_id",
        "editable",
    ];
}
