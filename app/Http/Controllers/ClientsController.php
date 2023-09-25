<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function getClients()
    {
        return DB::table("clients")->get();
    }

    public function getClient($client_id)
    {
        return DB::table("clients")
        ->where("id", $client_id)
        ->first();
    }

    public function editClient(Request $request)
    {
        $this->validate($request, [
            "fullName" => "required",
            "phone"   => "required",
            "address" => "required",
        ]);

        $client = Client::where("id",$request->id)->update([
            'fullName'  => $request->fullName,
            'phone'     => $request->phone,
            'address'   => $request->address,
        ]);

        return $request;
    }

    public function deleteClient(Request $request)
    {
        $orders = Order::where("client_id", $request->id)->get();
        Order::where("client_id", $request->id)->delete();
        foreach ($orders as $order) {
            OrderDetail::where("order_id", $order->id)->delete();
        }

        $res = Client::where("id", $request->id)->delete();

        return $res;
    }


}
