<?php

namespace App\Http\Controllers;

use App\Models\CardType;
use Illuminate\Http\Request;

class CardTypesController extends Controller
{
    public function getCardTypes(){
        return CardType::all();
    }
}
