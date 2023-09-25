<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller
{
    public function getBrands()
    {
        return DB::table("brands")->get();
    }

    public function addBrand(Request $request)
    {
        $this->validate($request, [
            "name"   => "required",
        ]);

        $brand = Brand::create(['name'  => $request->name]);

        return $brand;

    }

    public function editBrand(Request $request)
    {
        $this->validate($request, [
            "name"   => "required",
        ]);

        Brand::where("id",$request->id)->update([
            'name' => $request->name,
        ]);

        return $request;
    }

    public function deleteBrand(Request $request)
    {
        Brand::where("id", $request->id)->delete();

        return $request;
    }
}
