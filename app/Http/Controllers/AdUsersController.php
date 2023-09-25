<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdUsersController extends Controller
{
    public function getAdUsers()
    {
        return DB::table("ad_users as p")
            ->orderBy("p.fullName")
            ->get();
    }

    public function getPartnersWithAds()
    {
        $adUsers = $this->getAdUsers();
        foreach ($adUsers as $key => $adUser) {
            $ads = DB::table("ads as a")
                                    ->where("a.ad_user_id", $adUser->id)
                                    ->orderBy("a.created_at", "desc")
                                    ->get();

            $adUsers[$key]->ads = $ads;
        }

        return $adUsers;
    }

    public function addAdUser(Request $request)
    {
        $this->validate($request, [
            "fullName"  => "required",
            "phone"     => "required",
        ]);

        $adUser = AdUser::create([
            'fullName'  => $request->fullName,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'company'   => $request->company,
        ]);

        $adUser->ads = [];

        return $adUser;
    }

    public function editAdUser(Request $request)
    {
        AdUser::where("id", $request->id)->update([
            "fullName"  => $request->fullName,
            "phone"     => $request->phone,
            "address"   => $request->address,
            "company"   => $request->company,
        ]);

        return $request;
    }

    public function deleteAdUser(Request $request)
    {
        Ad::where("ad_user_id", $request->id)->delete();
        return AdUser::where("id", $request->id)->delete();

    }
}
