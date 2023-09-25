<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrdersController extends Controller
{
    public function getOrders($store_id = 1, $status = 0){

        $orderDetails = DB::table("order_details as od")
        ->select("od.order_id", DB::raw("(SUM(((CEIL((od.sell_price * od.dollar_rate)/100) * 100) - (CEIL((od.discount * od.dollar_rate)/100) * 100)) * od.count)) as total"))
        ->groupBy('od.order_id');

        $query = DB::table("orders as o")
            ->select("o.*", "c.fullName", "c.phone", "c.address", "od.total as totalUzb", "s.name as store_name")
            ->leftJoin("clients as c", "c.id", "=", "o.client_id")
            ->leftJoin("stores as s", "s.id", "=", "o.store_id")
            ->leftJoinSub($orderDetails, "od", function($join){
                $join->on("od.order_id", "=", "o.id");
            })
            ->where("o.status", $status)
            ->orderBy("o.created_at", "desc");

            if($store_id != 1) {
                $query = $query->where("o.store_id", $store_id);
            }

            return $query->get();
    }

    public function getOrder($id)
    {
        return DB::table("orders as o")
            ->select("o.*", "c.fullName", "c.phone", "c.address")
            ->leftJoin("clients as c", "c.id", "=", "o.client_id")
            ->where("o.id", $id)
            ->first();
    }

    public function addOrder(Request $request)
    {
        $client_id      = $request->client_id;
        $products       = $request->products;
        $store_id       = $request->store_id;

        $total = 0;
        foreach ($products as $product) {
            $total += ($product["sell_price"] - $product["discount"]) * $product["count"];
        }

        $dollarRate = $this->getSettingsItem("DOLLAR_RATE");
        $orderId = $this->generateOrderId();

        $order = Order::create([
            "oid"       => $orderId,
            "client_id" => $client_id,
            "total"     => $total,
            "dollarRate"=> $dollarRate->value,
            "status"    => 0,
            "store_id"  => $store_id, //asosiy dukon
        ]);

        foreach ($products as $product) {
            OrderDetail::create([
                "order_id"      => $order->id,
                "product_id"    => $product["product_id"],
                "category_id"   => $product["category_id"],
                "count"         => $product["count"],
                "sell_price"    => $product["sell_price"],
                "price"         => $product["price"],
                "discount"      => $product["discount"],
                "dollar_rate"   => $dollarRate->value,
            ]);
        }

        return response()->json($order);

    }

    public function generateOrderId(){
        $id = IdGenerator::generate(['table' => 'orders', 'field' => 'oid', 'length' => 10, 'prefix' => 'BR-']);
  
        return $id;
    }

    public function getSettingsItem($name)
    {
        return Setting::where("name", $name)->first();
    }

    public function updateOrder(Request $request)
    {

        return "afafafa";

        // $order_id   = $request->order_id;
        // $arr        = $request->arr;

        // Order::where("id", $order_id)->update($arr);

        // return $arr;

    }

    public function deleteOrder(Request $request)
    {
        Order::where("id", $request->id)->delete();
        OrderDetail::where("order_id", $request->id)->delete();

        return $request;
    }

    public function getClientOrders($client_id){

        $orders = DB::table("orders as o")
        ->select("o.client_id", "o.status", "o.created_at as order_date", "od.*", "od.price as price", "od.sell_price as sell_price", "c.name as country", "c.name_ru as country_ru", "b.name as brand",
            "pt.name", "pt.name_ru", "p.code", "p.country_id", "p.brand_id", "p.measurement", "p.picture", "p.description", "p.min_count"
        )
        ->leftJoin("order_details as od", "od.order_id", "=", "o.id")
        ->leftJoin("products as p", "p.id", "=", "od.product_id")
        ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
        ->leftJoin("countries as c", "c.id", "=", "p.country_id")
        ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
        ->where("o.client_id", $client_id)
        ->orderBy("o.created_at", "DESC")
        ->orderBy("pt.name", "asc")
        ->orderBy("p.code", "asc")
        ->get();

        $clientOrders = array();
        foreach($orders as $order){
            $clientOrders[$order->order_id][] = $order;
        }

        rsort($clientOrders);
        return $clientOrders;
    }

    public function assignToStore(Request $request){

        $order_id   = $request->order_id;
        $store_id   = $request->store_id;

        return Order::where("id", $order_id)->update(["store_id" => $store_id]);
    }

}
