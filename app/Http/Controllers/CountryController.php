<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function getCountries()
    {
        return DB::table("countries")->get();
    }

    public function addCountry(Request $request)
    {
        $this->validate($request, [
            "name"      => "required",
            "name_ru"   => "required",
        ]);

        $country = Country::create([
            'name'      => $request->name,
            'name_ru'   => $request->name_ru,
        ]);

        return $country;

    }

    public function editCountry(Request $request)
    {
        $this->validate($request, [
            "name"      => "required",
            "name_ru"   => "required",
        ]);

        Country::where("id",$request->id)->update([
            'name'      => $request->name,
            'name_ru'   => $request->name_ru,
        ]);

        return $request;
    }

    public function deleteCountry(Request $request)
    {
        Country::where("id", $request->id)->delete();

        return $request;
    }

}
