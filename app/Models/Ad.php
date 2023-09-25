<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "title",
        "ad_user_id",
        "picture",
        "active",
        "sort",
    ];
}
