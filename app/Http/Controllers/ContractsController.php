<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\IncomeSets;
use App\Models\RawMaterial;
use App\Models\RawMaterialDetail;
use App\Models\RowMaterial;
use App\Models\RowMaterialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractsController extends Controller
{
    public function getContract($contract_id = null)
    {
        return IncomeSets::find($contract_id);
    }

    public function updateContractTotal(Request $request)
    {
        $this->validate($request, [
            'total'          => "required",
        ]);

        IncomeSets::where('id', $request->id)->update([
            'total'          => $request->total,
        ]);

        return response()->json($request);
    }

    public function deleteContract(Request $request)
    {
        $this->validate($request, [
            'id'        => "required",
        ]);

        IncomeSets::where('id', $request->id)->delete();
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
}
