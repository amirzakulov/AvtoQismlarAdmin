<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "oid",
        "client_id",
        "total",
        "dollarRate",
        "status",
        "store_id",
    ];
}
