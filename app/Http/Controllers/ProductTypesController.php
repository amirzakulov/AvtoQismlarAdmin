<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductTypesController extends Controller
{
    public function getProductTypes()
    {
        return DB::table("product_types")->orderBy("name")->get();
    }
}
