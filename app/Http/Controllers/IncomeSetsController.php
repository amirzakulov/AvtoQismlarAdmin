<?php

namespace App\Http\Controllers;

use App\Models\IncomeSets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeSetsController extends Controller
{
    public function getIncomeSets()
    {
        return DB::table("income_sets as c")
            ->select("c.*", "u.fullName")
            ->leftJoin("users as u", "u.id", "=", "c.user_id")
            ->get();
    }

    public function getIncomeSet($contract_id = null)
    {
        return IncomeSets::find($contract_id);
    }

    public function updateIncomeSet(Request $request)
    {
        $contract_id    = $request->id;
        $contract_data  = $request->contract;

        $res = IncomeSets::where('id', $contract_id)->update(
            $contract_data
        );

        return response()->json($request);
    }

    /*

    public function deleteContract(Request $request)
    {
        $this->validate($request, [
            'id'        => "required",
        ]);

        InoutSet::where('id', $request->id)->delete();
        RawMaterial::where('set_id', $request->id)->delete();

        $raw_materials = DB::table("raw_material_details as r")->where("r.set_id", $request->id)->get();
        RawMaterialDetail::where('set_id', $request->id)->delete();

        if($request->inout == 2) {

            foreach ($raw_materials as $raw_material) {

                $outCount   = $raw_material->count;
                $outPackage = $raw_material->packageCount;

                $balance = Balance::where('raw_material_id', $raw_material->raw_material_id)->first();
                $count     = $balance->count;
                $package   = $balance->packageCount;

                $res = Balance::where('raw_material_id', $raw_material->raw_material_id)->update([
                    'packageCount'  => $outCount + $count,
                    'count'         => $outPackage + $package,
                ]);
            }

            return $res;

        } else {
            return Balance::where('set_id', $request->id)->delete();
        }

    }

    */

}
