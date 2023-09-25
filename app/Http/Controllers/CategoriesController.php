<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function getCategories()
    {
        return DB::table("categories as c")->orderBy("c.sort")->get();
    }

    public function addCategory(Request $request)
    {
        $this->validate($request, [
            "name"   => "required",
        ]);

        $category = Category::create([
            'name' => $request->name,
            'picture' => $request->picture,
            'sort' => $request->sort,
        ]);

        return $category;

    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            "name"   => "required",
        ]);

        Category::where("id",$request->id)->update([
            'name' => $request->name,
            'picture' => $request->picture,
            'sort' => $request->sort,
        ]);

        return $request;
    }

    public function deleteCategory(Request $request)
    {
        return Category::where("id", $request->id)->delete();
    }

    public function imageUpload(Request $request)
    {
        $this->validate($request, [
            'file' => "required|image|mimes:jpg,jpeg,png",
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/categories/'), $picName);

        return $picName;

    }
}
