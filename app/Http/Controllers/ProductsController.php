<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\IncomeProduct;
use App\Models\ProductType;
use App\Models\ProductsImage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductCategoriesController;

class ProductsController extends Controller
{

    public function getProductsReport()
    {
        $products = DB::table("products as p")
            ->select("p.*", "c.name as country", "c.name_ru as country_ru", "b.name as brand")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->orderBy("p.name")
            ->groupBy("p.id")
            ->get();

        foreach ($products as $key => $product){
           $categories = DB::table("product_categories as pc")
            ->select("pc.category_id", "c.name as category_name")
            ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
            ->where("pc.product_id", $product->id)
            ->get();

            $products[$key]->categories = $categories;
        }

        return $products;

    }

    public function getProducts()
    {
        $products = DB::table("products as p")
            ->select("p.*", "c.name as country", "c.name_ru as country_ru", "b.name as brand")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->orderBy("p.name")
            ->groupBy("p.id")
            ->get();

        foreach ($products as $key => $product){
           $categories = DB::table("product_categories as pc")
            ->select("pc.category_id", "c.name as category_name")
            ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
            ->where("pc.product_id", $product->id)
            ->get();

            $products[$key]->categories = $categories;
        }

        return $products;

    }

    public function getProduct($product_id)
    {
        $product = DB::table("products as p")
            ->select("p.*", "pt.name", "pt.name_ru", "c.name as country", "c.name_ru as country_ru", "b.name as brand")
            ->leftJoin("product_types as pt", "pt.id", "=", "p.product_type_id")
            ->leftJoin("countries as c", "c.id", "=", "p.country_id")
            ->leftJoin("brands as b", "b.id", "=", "p.brand_id")
            ->where("p.id", $product_id)
            ->first();

        $categories = DB::table("product_categories as pc")
            ->select("pc.category_id", "c.name as category_name")
            ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
            ->where("pc.product_id", $product_id)
            ->get();

        $product->categories = $categories;

        return $product;
    }

    public function addProduct(Request $request)
    {

        $product_type_id = $request->product_type_id;
        if(!$product_type_id) {
            $this->validate($request, [
                'name'          => "required",
                'name_ru'       => "required",
            ]);
        }

        $this->validate($request, [
            "categories"    => "required",
            "country_id"    => "required",
            "brand_id"      => "required",
            "measurement"   => "required",
        ]);


        if(!$product_type_id) {
            $product_type = ProductType::create([
                'name'          => $request->name,
                'name_ru'       => $request->name_ru,
            ]);

            $product_type_id = $product_type->id;
        } else {
            $pt = ProductType::where("id", $product_type_id)->first();
            $request->name      = $pt->name;
            $request->name_ru   = $pt->name_ru;

        }

        $product = Product::create([
            'product_type_id'=> $product_type_id,
            'name'          => $request->name,
            'name_ru'       => $request->name_ru,
            'code'          => $request->code,
            'country_id'    => $request->country_id,
            'brand_id'      => $request->brand_id,
            'measurement'   => $request->measurement,
            'price'         => $request->price,
            'sell_price'    => $request->sell_price,
            'discount'      => $request->discount,
            'count'         => 0,
            'guarantee'     => $request->guarantee ? 1:0,
            'picture'       => $request->picture,
            'description'   => $request->description,
            'min_count'     => 0,
        ]);

        foreach ($request->categories as $category_id) {
            ProductCategory::create([
                'product_id'  => $product->id,
                'category_id' => $category_id
            ]);
        }

        return $this->getProduct($product->id);
    }

    public function editProduct(Request $request)
    {
        $this->validate($request, [
            'name'          => "required",
            'name_ru'       => "required",
            "country_id"    => "required",
            "brand_id"      => "required",
            'measurement'   => "required",
        ]);

        ProductType::where("id", $request->product_type_id)->update([
            'name'          => $request->name,
            'name_ru'       => $request->name_ru,
        ]);

        Product::where('id', $request->id)->update([
            'name'          => $request->name,
            'name_ru'       => $request->name_ru,
            'code'          => $request->code,
            'country_id'    => $request->country_id,
            'brand_id'      => $request->brand_id,
            'measurement'   => $request->measurement,
            'price'         => $request->price,
            'sell_price'    => $request->sell_price,
            'discount'      => $request->discount,
            'count'         => 0,
            'guarantee'     => $request->guarantee ? 1:0,
            'picture'       => $request->picture,
            'description'   => $request->description,
            'min_count'     => 0,
        ]);

        return $this->getProduct($request->id);
    }

    public function updateProductPrice(Request $request)
    {
        $this->validate($request, ['price' => "required",]);

        Product::where('id', $request->product_id)->update([
            'price'     => $request->price,
            'sell_price'=> $request->sell_price,
            'discount'  => $request->discount,
        ]);

        return $request;
    }

    public function deleteProduct(Request $request)
    {
        $this->validate($request, [
            'id'        => "required",
        ]);

        Product::where('id', $request->id)->delete();
        ProductCategory::where('product_id', $request->id)->delete();
        ProductsImage::where('product_id', $request->id)->delete();

        $products_count = DB::table("products as p")
        ->where("product_type_id", $request->product_type_id)
        ->count();

        if($products_count == 0) {
            ProductType::where("id", $request->product_type_id)->delete();
        }

        return IncomeProduct::where('product_id', $request->id)->delete();
    }

    public function getCategories($product_id, $names = false)
    {
        $res = DB::table("product_categories as pc")
            ->select("pc.*", "c.name")
            ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
            ->where("pc.product_id", $product_id)
            ->get();

        $categories = array();
        if(!$names){
            foreach ($res as $category) {
                $categories[] = $category->category_id;
            }
        } else {
            foreach ($res as $key => $category) {
                $categories[$key]["category_id"] = $category->category_id;
                $categories[$key]["category_name"] = $category->name;
            }
        }

        return $categories;
    }

    public function getStockProducts()
    {
        return DB::table("products as p")
            ->select("p.*", "pt.name as name", "pt.name_ru as name_ru",
                "c.name as country", "c.name as country_ru", "b.name as brand"
            )
            ->leftJoin("product_types as pt", "pt.id","=","p.product_type_id")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->leftJoin("income_products as ip", "ip.product_id","=","p.id")
            ->orderBy("pt.name")
            ->get();
    }

    public function imageUpload(Request $request)
    {
        $this->validate($request, [
            'file' => "required|image|mimes:jpg,jpeg,png",
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/products/'), $picName);

        return $picName;

    }

    public function imagesUpload(Request $request)
    {
        $this->validate($request, [
            'file' => "required|image|mimes:jpg,jpeg,png",
        ]);

        $picName = time().rand(10,50).'.'.$request->file->extension();
        $request->file->move(public_path('uploads/products/'), $picName);

        return $picName;

    }

    public function getCategoryProducts($category_id)
    {
        $dollarRate = Setting::where("name", "DOLLAR_RATE")->first();

        $products = DB::table("products as p")
            ->select("p.*", "c.name as country", "c.name_ru as country_ru", "b.name as brand", "pc.category_id", "pc.category_id as dollar_rate")
            ->leftJoin("product_categories as pc", "pc.product_id","=","p.id")
            ->leftJoin("countries as c", "c.id","=","p.country_id")
            ->leftJoin("brands as b", "b.id","=","p.brand_id")
            ->where("pc.category_id", $category_id)
            ->orderBy("p.name")
            ->get();

        foreach($products as $key => $product){
            $products[$key]->dollar_rate = $dollarRate->value;

        }

        return $products;
    }

}
