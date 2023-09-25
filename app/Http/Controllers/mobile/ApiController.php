<?php

namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderDetailsController;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ad;
use App\Models\ProductCategory;
use App\Models\ProductsImage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ApiController extends Controller
{
    public function getCategories()
    {
        return DB::table("categories as c")->orderBy("c.sort")->get();
    }

    public function getProducts($category_id)
    {
        $dollarRate = $this->getSettingsItem("DOLLAR_RATE");

        $products = DB::table("products as p")
            ->select("p.*", "c.name as country", "c.name_ru as country_ru", "b.name as brand", "pc.category_id")
            ->leftJoin("product_categories as pc", "pc.product_id","=","p.id")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->where("pc.category_id", $category_id)
            ->orderBy("p.name")
            ->get();

        foreach($products as $key => $product){
            $products[$key]->dollar_rate = $dollarRate->value;
            $products[$key]->categories = $this->getProductCategories($product->id);

        }

        return $products;
    }

    public function getProduct($product_id)
    {
        $dollarRate = $this->getSettingsItem("DOLLAR_RATE");

        $product = DB::table("products as p")
            ->select("p.*", "c.name as country", "c.name as country_ru", "b.name as brand")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->where("p.id", $product_id)
            ->orderBy("p.name")
            ->first();

        $product->dollar_rate = $dollarRate->value;
        $product->categories = $this->getProductCategories($product_id);

        return $product;
    }

    public function similarProducts($product_id)
    {
        $dollarRate = $this->getSettingsItem("DOLLAR_RATE");
        $product = DB::table("products")->where("id", $product_id)->first();
        $similarProducts = DB::table("products as p")
                ->select("p.*", "c.name as country", "c.name as country_ru", "b.name as brand", "pc.category_id")
                ->leftJoin("product_categories as pc", "pc.product_id","=","p.id")
                ->leftJoin("countries as c", "c.id","=","p.country_id")
                ->leftJoin("brands as b", "b.id","=","p.brand_id")
                ->where("p.product_type_id", $product->product_type_id)
                ->where("p.id", "!=", $product->id)
                ->where("pc.category_id", "!=", null)
                ->groupBy("p.id")
                ->orderBy("b.name")
                ->get();

        foreach($similarProducts as $sproduct) {
            $sproduct->dollar_rate = $dollarRate->value;
            $sproduct->categories  = $this->getProductCategories($sproduct->id);
        }

        return $similarProducts;

    }

    public function getProductImages($product_id)
    {
        return ProductsImage::where("product_id", $product_id)->get();
    }

    public function getProductWithSimilarProducts($product_id)
    {
        $data = array(
            "product"           => $this->getProduct($product_id),
            "similarProducts"   => $this->similarProducts($product_id),
            "productImages"     => $this->getProductImages($product_id)
        );

        return response()->json($data);
    }

    public function createOrder(Request $request)
    {
        $client_id      = $request->client_id;
        $products       = $request->products;

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
            "dollarRate" => $dollarRate->value,
            "status"    => 0,
            "store_id"  => 1, //asosiy dukon
        ]);

        foreach ($products as $product) {
            OrderDetail::create([
                "order_id"      => $order->id,
                "product_id"    => $product["id"],
                "category_id"   => $product["categories"][0]["id"],
                "count"         => $product["count"],
                "sell_price"    => $product["sell_price"],
                "price"         => $product["price"],
                "discount"      => $product["discount"],
                "dollar_rate"   => $product["dollar_rate"],
            ]);
        }

        return response()->json($order);
    }

    public function getClientOrders($client_id)
    {
        $clientOrder    = array();
        $orders         = DB::table("orders")->where("client_id", $client_id)->orderBy("orders.created_at", "desc")->get();
        $order_status   = array("yangi", "yo'lda", "bajarilgan");

        foreach ($orders as $order) {
            $orderDetail = OrderDetailsController::getOrderProducts($order->id);

            $clientOrder[] = array(
                "id" => $order->id,
                "oid" => $order->oid,
                "date" => date("d.m.Y", strtotime($order->created_at)),
                "total" => $order->total,
                "status" => $order->status,
                "products" => $orderDetail,
                );
        }

        return $clientOrder;
    }

    public function updateClientData(Request $request)
    {
        $client_id      = $request->client_id;
        $user_field     = $request->userField;
        $user_detail    = $request->userDetail;
        $user_detail    = trim($user_detail, ' ');

        Client::where("id", $client_id)->update([$user_field => trim($user_detail)]);

        return response()->json(json_encode(true));
    }

    public function deleteClient(Request $request)
    {
        if($request->phone != "+998916910036") {
            $orders = Order::where("client_id", $request->client_id)->get();
            Order::where("client_id", $request->client_id)->delete();
            foreach ($orders as $order) {
                OrderDetail::where("order_id", $order->id)->delete();
            }

            $res = DB::table("clients")->where("id", $request->client_id)->delete();
        } else {
            $res = 0;
        }


        return $res;
    }

    public function getSettingsItem($name)
    {
        return Setting::where("name", $name)->first();
    }

    public function getClientsCount()
    {
        $peopleCount = Setting::where("name", "PEOPLE_COUNT")->first();
        $clientsCount = Client::all()->count();

        return $peopleCount->value + $clientsCount;
    }

    public function getAds()
    {
        $count = $this->getClientsCount();

        $ads = Ad::where("active", 1)->select("picture")->get();
        $images = array();
        foreach ($ads as $ad) {
            $images[] = $ad->picture;
        }

        return response()->json([
            'pictures' => $images,
            'clientsCount' => $count
        ]);
    }

    public function getCards(){
        $cards = DB::table("cards as c")
        ->select("c.*", "ct.name as type", "ct.icon")
        ->leftJoin("card_types as ct", "ct.id", "=", "c.type_id")
        ->where("c.active", "=", 1)
        ->get();

        return response()->json($cards);
    }

    public function generateOrderId(){
        $id = IdGenerator::generate(['table' => 'orders', 'field' => 'oid', 'length' => 10, 'prefix' => 'BR-']);

        return $id;
    }

    public function getProductCategories($product_id){
        return DB::table("product_categories as pc")
        ->select("pc.product_id", "c.*")
        ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
        ->where("pc.product_id", $product_id)
        ->get();
    }



}
