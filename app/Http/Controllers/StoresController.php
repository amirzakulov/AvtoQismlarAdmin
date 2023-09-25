<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{
    public function getStores(){
        return DB::table("stores")->get();
    }

    public function getStore($id){
        return DB::table("stores")->where("id", $id)->first();
    }

    public function addStore(Request $request) {
        $this->validate($request, [
            'name' => 'required'
        ]);

        return Store::create([
            'name'      => $request->name,
            'address'   => $request->address,
            'phone'     => $request->phone,
        ]);
    }

    public function editStore(Request $request) {
        $this->validate($request, [
            'name' => 'required'
        ]);

        Store::where("id", $request->id)->update([
            'name'      => $request->name,
            'address'   => $request->address,
            'phone'     => $request->phone,
        ]);

        return $this->getStore($request->id);
    }

    public function deleteStore(Request $request){
        return Store::where("id", $request->id)->delete();
    }
}
