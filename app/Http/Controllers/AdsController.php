<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    public function getAds($ad_user_id)
    {
        return DB::table("ads as a")
            ->where("a.ad_user_id", $ad_user_id)
            ->orderBy("a.created_at", "desc")
            ->get();
    }

    public function getAd($id)
    {
        return DB::table("ads as a")->where("a.id", $id)->first();
    }

    public function addAd(Request $request)
    {
        $this->validate($request, [
            "title"  => "required",
        ]);

        $partner_ad = Ad::create([
            'title'         => $request->title,
            'ad_user_id'    => $request->partner_id,
            'picture'       => $request->picture,
            'active'        => 1,
            'sort'          => $request->sort,
        ]);

        return $partner_ad;
    }

    public function editAd(Request $request)
    {
        Ad::where("id", $request->id)->update([
            "title"     => $request->title,
            "picture"   => $request->picture,
            "sort"      => $request->sort,
        ]);

        return $this->getAd($request->id);
    }


    public function adPictureUpload(Request $request)
    {
        $this->validate($request, [
            'file' => "required|image|mimes:jpg,jpeg,png",
        ]);

        $picName = time().rand(10,50).'.'.$request->file->extension();
        $request->file->move(public_path('uploads/ads/'), $picName);

        return $picName;
    }

    public function changeStatus(Request $request)
    {
        $ad = $this->getAd($request->id);
        $status = $ad->active ? 0:1;

        Ad::where("id", $request->id)->update(["active" => $status]);

        return $status;
    }

    public function deleteAd(Request $request)
    {
        return Ad::where("id", $request->id)->delete();
    }

}
