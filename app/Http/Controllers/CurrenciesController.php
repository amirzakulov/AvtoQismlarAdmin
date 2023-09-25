<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrenciesController extends Controller
{
    public function getCurrencies()
    {
        return DB::table("currencies")->get();

    }
}
