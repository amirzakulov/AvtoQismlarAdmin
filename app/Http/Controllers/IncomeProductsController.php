<?php

namespace App\Http\Controllers;

use App\Models\IncomeProduct;
use App\Models\IncomeSets;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeProductsController extends Controller
{
    public function addIncomeProducts(Request $request)
    {
        $products   = $request->products;
        $user_id    = 1;
        $dollar_rate = 11000;

        $total = 0;
        foreach ($products as $product) {
            $product_cost = $product["count"] * $product["price"];
            $total += $product_cost;
        }

        $inSet = IncomeSets::create([
            "name"                  => "PARTIYA",
            "user_id"               => $user_id,
            "total"                 => $total,
            "editable"              => 1,
        ]);

        //Rmstocks
        foreach ($products as $product) {

           IncomeProduct::create([
                'product_id'    => $product["product_id"],
                'price'         => $product["price"],
                'count'         => $product["count"],
                'dollar_rate'   => $dollar_rate,
                'set_id'        => $inSet->id,
                'editable'      => 1,
            ]);

            $product_count = $this->getProductCount($product["product_id"]);
            Product::where("id", $product["product_id"])->update(["count" => $product_count]);

        }

        $res = IncomeSets::find($inSet->id);

        return response()->json($res);
    }

    public function addIncomeProduct(Request $request)
    {
        $dollar_rate = 11000;
        $res = IncomeProduct::create([
            'product_id'    => $request->product_id,
            'price'         => $request->price,
            'count'         => $request->count,
            'dollar_rate'   => $dollar_rate,
            'set_id'        => $request->contract_id,
            'editable'      => 1,
        ]);

        $product_count = $this->getProductCount($request->product_id);
        Product::where("id", $request->product_id)->update(["count" => $product_count]);
        $product = $this->getIncomeProduct($res->id);

        return $product;

    }

    public function editIncomeProduct(Request $request)
    {
        IncomeProduct::where('id', $request->id)->update([
            'count' => $request->count,
            'price' => $request->price,
        ]);

        $product_count = $this->getProductCount($request->product_id);
        Product::where("id", $request->product_id)->update(["count" => $product_count]);

        return response()->json($request);
    }

    public function getIncomeProducts($contract_id = null)
    {
        return DB::table("income_products as s")
            ->select("s.*", "o.name as set_name", "o.user_id", "c.name as country", "c.name_ru as country_ru", "b.name as brand",
                "pt.name", "pt.name_ru", "p.code", "p.country_id", "p.brand_id", "p.measurement", "p.picture", "p.description", "p.min_count")
            ->leftJoin("products as p", "p.id", "=", "s.product_id")
            ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
            ->leftJoin("income_sets as o", "o.id", "=", "s.set_id")
            ->leftJoin("countries as c", "c.id", "=", "p.country_id")
            ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
            ->where("s.set_id", $contract_id)
            ->get();
    }

    public function getIncomeProduct($id = null)
    {
        return DB::table("income_products as s")
            ->select("s.*", "o.name as set_name", "o.user_id",
                "pt.name", "pt.name_ru", "p.code", "p.country_id", "p.brand_id", "p.measurement", "p.picture", "p.description", "p.min_count")
            ->leftJoin("products as p", "p.id", "=", "s.product_id")
            ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
            ->leftJoin("income_sets as o", "o.id", "=", "s.set_id")
            ->where("s.id", $id)
            ->first();
    }

    public function getProductCount($product_id)
    {
        return IncomeProduct::where("product_id", $product_id)
            ->sum("count");

    }
}
