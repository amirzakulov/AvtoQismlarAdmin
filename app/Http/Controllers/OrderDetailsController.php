<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailsController extends Controller
{
    public function getOrderProducts($order_id)
    {
        return DB::table("order_details as od")
            ->select("od.*", "od.price as price", "od.sell_price as sell_price", "c.name as country", "c.name_ru as country_ru", "b.name as brand",
                "pt.name", "pt.name_ru", "p.code", "p.country_id", "p.brand_id", "p.measurement", "p.picture", "p.description", "p.min_count",
                "ct.name as category_name"
            )
            ->leftJoin("orders as o", "o.id", "=", "od.order_id")
            ->leftJoin("products as p", "p.id", "=", "od.product_id")
            ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
            ->leftJoin("countries as c", "c.id", "=", "p.country_id")
            ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
            ->leftJoin("categories as ct", "ct.id", "=", "od.category_id")
            ->where("od.order_id", $order_id)
            ->orderBy("pt.name" )
            ->orderBy("p.code" )
            ->get();
    }

    public function getOrderProduct($order_id, $product_id)
    {
        return DB::table("order_details as od")
            ->select("od.*", "c.name as country", "c.name_ru as country_ru", "b.name as brand", "ct.name as category_name",
                "pt.name", "pt.name_ru", "p.code", "p.country_id", "p.brand_id", "p.measurement", "p.picture", "p.description", "p.min_count")
            ->leftJoin("products as p", "p.id", "=", "od.product_id")
            ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
            ->leftJoin("countries as c", "c.id", "=", "p.country_id")
            ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
            ->leftJoin("categories as ct", "ct.id", "=", "od.category_id")
            ->where("od.order_id", $order_id)
            ->where("od.product_id", $product_id)
            ->first();
    }

    public function addOrderProduct(Request $request)
    {
        $dollar_rate = Setting::where("name", "DOLLAR_RATE")->first();
        $OrderDetails = OrderDetail::create([
            'order_id'      => $request->order_id,
            'product_id'    => $request->product_id,
            'category_id'   => $request->category_id,
            'count'         => $request->count,
            'price'         => $request->price,
            'sell_price'    => $request->sell_price,
            'discount'      => $request->discount,
            'dollar_rate'   => $dollar_rate->value,
        ]);

        $order = $this->getTotalPrice($request->order_id);
        Order::where("id", $request->order_id)->update(["total" => $order->total]);

        $product = $this->getOrderProduct($request->order_id, $request->product_id);

        return $product;
    }

    public function editOrderProduct(Request $request)
    {
        $this->validate($request, ['price' => "required",]);

        OrderDetail::where('id', $request->id)->update([
            'count'     => $request->count,
            'discount'  => $request->discount,
        ]);

        $order = $this->getTotalPrice($request->order_id);
        Order::where("id", $request->order_id)->update(["total" => $order->total]);

        $product = $this->getOrderProduct($request->order_id, $request->product_id);

        return $request;
    }

    public function getTotalPrice($order_id)
    {
        $total = DB::table('order_details as od')
            ->select(DB::raw("(SUM(od.sell_price * od.count) - od.discount) as total"))
            ->where("od.order_id", $order_id)
            ->groupBy("od.order_id")
            ->first();

        return $total;
    }

    public function deleteOrderProduct(Request $request)
    {
        OrderDetail::where("id", $request->id)->delete();

        $order = $this->getTotalPrice($request->order_id);
        Order::where("id", $request->order_id)->update(["total" => $order->total]);

        return $request;
    }



}
