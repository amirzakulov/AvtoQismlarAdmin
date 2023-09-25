<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardsController extends Controller
{
    public function getCards(){
        return DB::table("cards as c")
        ->select("c.*", "ct.name as type", "ct.icon")
        ->leftJoin("card_types as ct", "ct.id", "=", "c.type_id")
        ->get();
    }

    public function getCard($id){
        return DB::table("cards as c")
        ->select("c.*", "ct.name as type", "ct.icon")
        ->leftJoin("card_types as ct", "ct.id", "=", "c.type_id")
        ->where("c.id", $id)
        ->first();
    }

    public function addCard(Request $request)
    {
        $this->validate($request, [
            "number"   => "required",
            "type_id"  => "required",
        ]);

        $card = Card::create([
            "number"    => $request->number,
            "type_id"   => $request->type_id,
            "active"    => $request->active,
        ]);

        return $this->getCard($card->id);
    }


    public function editCard(Request $request)
    {
        $this->validate($request, [
            "number"   => "required",
            "type_id"  => "required",
        ]);

        Card::where("id", $request->id)->update([
            "number"    => $request->number,
            "type_id"   => $request->type_id,
            "active"    => $request->active,
        ]);

        return $this->getCard($request->id);
    }

    public function deleteCard(Request $request)
    {
        Card::where("id", $request->id)->delete();

        return $request;
    }
}
