<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\IncomeProduct;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductCategoriesController;

class ReportsController extends Controller
{
    public function getProductsReport(Request $request) {
        $dates = $request->dates;
        $start_date = date("Y-m-d", strtotime($dates[0]));
        $end_date   = date("Y-m-d", strtotime($dates[1]));

        $query = DB::table("order_details as od")
        ->select("od.product_id", "p.name", "p.name_ru", "p.code", "p.measurement", "b.name as brand", "c.name as country", "c.name_ru as country_ru",
        DB::raw("SUM(od.count) as count"), DB::raw("AVG(od.price) as price"), DB::raw("AVG(od.sell_price) as sell_price"),
        DB::raw("AVG(od.discount) as discount"), "o.oid")
        ->leftJoin("orders as o", "o.id", "=", "od.order_id")
        ->leftJoin("products as p", "p.id", "=", "od.product_id")
        ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
        ->leftJoin("countries as c", "c.id", "=", "p.country_id")
        ->whereDate('od.created_at', '>=', $start_date)
        ->whereDate('od.created_at', '<=', $end_date)
        ->orderBy("p.name")
        ->groupBy("od.product_id");

        if($request->store_id != 0){
            $query->where("o.store_id", $request->store_id);
        }

        $products = $query->get();

        return $products;
    }

    public function getClientsReport(){
        $clients = DB::table("orders as o")
        ->select("o.client_id", DB::raw("SUM(o.total) as total"), "c.fullName", "c.phone", "c.address")
        ->leftJoin("clients as c", "c.id", "=", "o.client_id")
        ->orderBy("c.fullName")
        ->groupBy("o.client_id")
        ->get();

        return $clients;
    }

}
