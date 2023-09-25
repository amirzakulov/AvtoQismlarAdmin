<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsImage extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "id",
        "product_id",
        "picture",
        "sort",

    ];
}
