<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoriesController extends Controller
{

    public function getProductCategories($product_id){
        return DB::table("product_categories as pc")
        ->select("pc.category_id", "c.name as category_name")
        ->leftJoin("categories as c", "c.id", "=", "pc.category_id")
        ->where("pc.product_id", $product_id)
        ->get();
    }

    public function editProductCategories(Request $request)
    {
        $this->validate($request, [
            "product_id"    => "required",
            "categories"    => "required",
        ]);


        ProductCategory::where('product_id', $request->product_id)->delete();

        foreach ($request->categories as $category_id) {
            ProductCategory::create([
                'product_id'  => $request->product_id,
                'category_id' => $category_id
            ]);
        }

        $categories = $this->getProductCategories($request->product_id);
        return response()->json($categories);
    }
}
