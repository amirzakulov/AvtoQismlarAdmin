<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getSettings()
    {
        return Setting::where("public", 1)->get();
    }

    public function updateSettingsItem(Request $request)
    {
        $settings = $request->settings;

        foreach ($settings as $config) {
            $res = Setting::where("name", $config["name"])->update(["value" => $config["value"]]);
        }

        return $res;

    }
}
