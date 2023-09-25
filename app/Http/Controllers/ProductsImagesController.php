<?php

namespace App\Http\Controllers;

use App\Models\ProductsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductsImagesController extends Controller
{
    public function addProductImages(Request $request)
    {
        $product_id = $request->product_id;
        $images     = $request->images;

        ProductsImage::where("product_id", $product_id)->delete();

        $res = false;
        foreach ($images as $image) {

            $res = ProductsImage::create([
                'picture'    => $image['picture'],
                'product_id' => $product_id,
            ]);
        }

        return $res;

    }

    public function getProductImages($product_id)
    {
        return ProductsImage::where("product_id", $product_id)->get();

    }

    public function deleteImage(Request $request) {

        unlink(public_path('uploads/products/'.$request->picture));

        return ProductsImage::where('id', $request->id)->delete();
    }
}
