<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\IncomeSets;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\RawMaterialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RmstockController extends Controller
{
    public function getInout()
    {
        return DB::table("inout_sets as c")
            ->select("c.*", "u.fullName", "df.name as department_from", "dt.name as department_to")
            ->leftJoin("users as u", "u.id", "=", "c.user_id")
            ->leftJoin("departments as df", "df.id", "=", "c.from_department_id")
            ->leftJoin("departments as dt", "dt.id", "=", "c.to_department_id")
            ->get();
    }

    public function getIncomeProducts(Request $request, $contract_id = null)
    {
        return DB::table("raw_materials as s")
            ->select("s.*", "d.count", "d.packageCount", "d.inout", "d.set_id", "d.set_name", "d.user_id", "d.created_at", "d.updated_at",
                "p.name", "p.code", "p.color", "p.country", "p.measurement", "p.latest_price", "p.picture", "p.description", "p.min_count")
            ->leftJoin("raw_material_details as d", "d.raw_material_id", "=", "s.id")
            ->leftJoin("products as p", "p.id", "=", "s.product_id")
            ->where("s.set_id", $contract_id)
            ->get();
    }



    public function getContractProducts(Request $request, $contract_id = null)
    {
        return DB::table("raw_material_details as d")
            ->select("d.*", "rm.currency_id", "rm.editable",
                "p.name", "p.code", "p.color", "p.country", "p.measurement", "p.latest_price", "p.picture", "p.description", "p.min_count")
            ->leftJoin("products as p", "p.id", "=", "d.product_id")
            ->leftJoin("raw_materials as rm", "rm.id", "=", "d.raw_material_id")
            ->where("d.set_id", $contract_id)
            ->get();
    }

    public function addIncomeProducts(Request $request)
    {
        $contract       = $request->contract;
        $products       = $request->products;
        $currency_id    = $request->currency_id;

        //Contract uchun
        $last_contract_id = DB::table('inout_sets')->max('id');
        $new_contract_id    = $last_contract_id + 1;
        $new_contract_name  = "DOCIN".$new_contract_id;
        $InOut = 1;
        $user_id = 1;

        $total = 0;
        foreach ($products as $product) {
            $product_cost = $product["count"] * $product["price"];
            $total += $product_cost;
        }

        IncomeSets::create([
            "name"                  => $new_contract_name,
            "employee_id"           => $contract["client_id"],
            "user_id"               => $user_id,
            "total"                 => $total,
            "inout"                 => $InOut,
            "transportation_cost"   => $contract["transportation_cost"],
            "from_department_id"    => $contract["from_department_id"],
            "to_department_id"      => $contract["to_department_id"],
            "currency_id"           => $currency_id,
            "editable"              => 1,
        ]);

        //Rmstocks
        foreach ($products as $key => $product) {

            $rm = RawMaterial::create([
                'product_id'    => $product["product_id"],
                'price'         => $product["price"],
                'currency_id'   => $currency_id,
                'set_id'        => $new_contract_id,
                'editable'      => 1,
            ]);

            $rmd = RawMaterialDetail::create([
                'raw_material_id' => $rm->id,
                'count'         => $product["count"],
                'packageCount'  => $product["packageCount"],
                'inout'         => 1,
                'set_id'        => $new_contract_id,
                'set_name'      => $new_contract_name,
                'user_id'       => $user_id,
                'product_id'    => $product["product_id"],
                'price'         => $product["price"],
            ]);

            Balance::create([
                'raw_material_id'   => $rm->id,
                'product_id'        => $product["product_id"],
                'count'             => $product["count"],
                'packageCount'      => $product["packageCount"],
                'set_id'            => $new_contract_id,
                'set_name'          => $new_contract_name,
                'price'             => $product["price"],
            ]);

            $latest_price = $product["price"];
            $dollar_rate = 11000;
            if($request->currency_id == 2) {
                $latest_price = $product["price"] / $dollar_rate;
            }
            Product::where('id', $product["product_id"])->update([
                'latest_price' => $latest_price,
            ]);
        }

        $res = IncomeSets::find($new_contract_id);

        return response()->json($res);
    }

    public function addIncomeProduct(Request $request)
    {
        //Rmstocks
        $rm = RawMaterial::create([
            'product_id'    => $request->product_id,
            'price'         => $request->price,
            'currency_id'   => $request->currency_id,
            'set_id'        => $request->contract_id,
            'editable'      => 1,
        ]);

        RawMaterialDetail::create([
            'raw_material_id' => $rm->id,
            'count'         => $request->count,
            'packageCount'  => $request->packageCount,
            'inout'         => $request->inout,
            'set_id'        => $request->contract_id,
            'set_name'      => $request->contract_name,
            'user_id'       => 1,
            'product_id'    => $request->product_id,
            'price'         => $request->price,
        ]);

        Balance::create([
            'raw_material_id'   => $rm->id,
            'product_id'        => $request->product_id,
            'count'             => $request->count,
            'packageCount'      => $request->packageCount,
            'set_id'            => $request->contract_id,
            'set_name'          => $request->contract_name,
            'price'             => $request->price,
        ]);

        $inserted_product = array(
            'packageCount'  => $request->packageCount,
            'count'         => $request->count,
            'price'         => $request->price,
            'currency_id'   => $request->currency_id,
            'updated_at'    => date("d.m.Y H:i")
        );

        return $inserted_product;
    }

    public function editIncomeProduct(Request $request)
    {
        //Rmstocks
        RawMaterial::where("id", $request->raw_material_id)->update([
            'price'         => $request->price,
        ]);
        RawMaterialDetail::where(array('id' => $request->raw_material_id, "inout" => 1))->update([
            'count'         => $request->count,
            'packageCount'  => $request->packageCount,
            'price'         => $request->price,
        ]);

        Balance::where('raw_material_id', $request->raw_material_id)->update([
            'price'         => $request->price,
            'count'         => $request->count,
            'packageCount'  => $request->packageCount,
        ]);

        $product = Product::find($request->product_id);
        $data = array(
            'name'          => $product->name,
            'product_id'    => $request->product_id,
            'count'         => $request->count,
            'price'         => $request->price,
            'packageCount'  => $request->packageCount,
            'updated_at'    => date("d.m.Y H:i")
        );

        return response()->json($data);

    }

    public function deleteIncomeProduct(Request $request)
    {
        $this->validate($request, [
            'id'        => "required",
        ]);

        $res = RawMaterial::where('id', $request->raw_material_id)->delete();
        RawMaterialDetail::where('raw_material_id', $request->raw_material_id)->delete();
        Balance::where('raw_material_id', $request->raw_material_id)->delete();

        return $res;
    }

    /** Row Materials **/
    public function getRawMaterials()
    {
        return DB::table("balances as b")
            ->select("b.*", "p.name", "p.code", "p.color", "p.country", "p.measurement", "p.latest_price", "p.picture", "p.description", "p.min_count")
            ->leftJoin("products as p", "p.id", "=", "b.product_id")
            ->orderBy("p.name", "asc")
            ->orderBy("b.created_at", "asc")
            ->get();
    }

    public function getRawMaterial(Request $request, $raw_material_id)
    {
        return DB::table("balances as b")
            ->select("b.*", "r.price")
            ->leftJoin("raw_materials as r", "r.id", '=', "b.raw_material_id")
            ->where("b.raw_material_id", $raw_material_id)
            ->first();
    }

    public function addOutcomeProducts(Request $request)
    {
        $contract       = $request->contract;
        $products       = $request->products;

        $last_contract_id = DB::table('inout_sets')->max('id');
        $new_contract_name  = "DOCOUT".($last_contract_id + 1);
        $InOut = 2;
        $user_id = 1;

        $total = 0;
        $raw_materials = array();
        foreach ($products as $key => $product) {
            $raw_material = $this->getRawMaterial($request, $product["raw_material_id"]);
            $product_cost = $product["count"] * $raw_material->price;
            $total += $product_cost;

            $raw_materials[$key]["product_id"]  = $raw_material->product_id;
            $raw_materials[$key]["price"]       = $raw_material->price;

        }

        $inOutSet = IncomeSets::create([
            "name"                  => $new_contract_name,
            "employee_id"           => $contract["employee_id"],
            "user_id"               => $user_id,
            "total"                 => $total,
            "inout"                 => $InOut,
            "transportation_cost"   => 0,
            "from_department_id"    => 2,
            "to_department_id"      => $contract["to_department_id"],
            "currency_id"           => 1,
            "editable"              => 1,
        ]);

        $new_contract_id    = $inOutSet->id;

        //Rmstocks
        foreach ($products as $key => $product) {

            RawMaterialDetail::create([
                'raw_material_id' => $product["raw_material_id"],
                'count'         => (-1) * $product["count"],
                'packageCount'  => (-1) * $product["packageCount"],
                'inout'         => 2,
                'set_id'        => $new_contract_id,
                'set_name'      => $new_contract_name,
                'user_id'       => $user_id,
                'product_id'    => $raw_materials[$key]["product_id"],
                'price'         => $raw_materials[$key]["price"],
            ]);

            RawMaterial::where('id', $product["raw_material_id"])->update(["editable" => 0]);

            $this->updateBalance($product["raw_material_id"]);

        }
    }

    public function editOutcomeProduct(Request $request)
    {
        //Rmstocks
        RawMaterialDetail::where('id', $request->id)->update([
            'count'         => (-1) * $request->count,
            'packageCount'  => (-1) * $request->packageCount,
        ]);

        $balance = $this->updateBalance($request->raw_material_id);

        $data = array(
            'name'          => $request->name,
            'product_id'    => $request->product_id,
            'count'         => $request->count,
            'price'         => $request->price,
            'packageCount'  => $request->packageCount,
            'updated_at'    => date("d.m.Y H:i")
        );

        return response()->json($data);

    }

    private function updateBalance($raw_material_id){
        $raw_material_details = RawMaterialDetail::where('raw_material_id', $raw_material_id)
            ->selectRaw("SUM(count) as count")
            ->selectRaw("SUM(packageCount) as packageCount")
            ->groupBy('raw_material_id')
            ->first();

        $balancePackage = $raw_material_details->packageCount;
        $balanceCount   = $raw_material_details->count;
        if( $balanceCount > 0) {
            return Balance::where('raw_material_id', $raw_material_id)->update([
                'packageCount'  => $balancePackage,
                'count'         => $balanceCount,
            ]);
        } else {
            return Balance::where('raw_material_id', $raw_material_id)->delete();
        }
    }

    public function deleteOutcomeProduct(Request $request)
    {
        $this->validate($request, [
            'id'        => "required",
        ]);

        RawMaterialDetail::where('id', $request->id)->delete();
        $res = $this->updateBalance($request->raw_material_id);

        return $res;

    }

    public function getStock(Request $request, $date = null)
    {
        if(is_null($date)) $date = date("Y-m-d");
        else $date = date("Y-m-d", strtotime($date));


        $d = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
        $day = $d->modify('last day of previous month');
        $prev_month = $day->format('Y-m-d');

        $balance_previous_month = $this->getBalance($prev_month);
        $balance = $this->getBalance($date);
        $inProducts = $this->getInOutProductsByDate(1);
        $outProducts = $this->getInOutProductsByDate(2);


        foreach ($balance as $product_id => $product) {
            $balance[$product_id]->prev_month_name        = $balance_previous_month[$product_id]->name;
            $balance[$product_id]->prev_month_measurement = $balance_previous_month[$product_id]->measurement;
            $balance[$product_id]->prev_month_code        = $balance_previous_month[$product_id]->code;
            $balance[$product_id]->prev_month_country     = $balance_previous_month[$product_id]->country;
            $balance[$product_id]->prev_month_price       = $balance_previous_month[$product_id]->price;
            $balance[$product_id]->prev_month_dollar_rate = $balance_previous_month[$product_id]->dollar_rate;
            $balance[$product_id]->prev_month_currency_id = $balance_previous_month[$product_id]->currency_id;
            $balance[$product_id]->prev_month_dollar_rate = $balance_previous_month[$product_id]->dollar_rate;
            $balance[$product_id]->prev_month_count       = $balance_previous_month[$product_id]->count;
            $balance[$product_id]->prev_month_packageCount = $balance_previous_month[$product_id]->packageCount;
            $balance[$product_id]->prev_month_set_name    = $balance_previous_month[$product_id]->set_name;
            $balance[$product_id]->prev_month_inout       = $balance_previous_month[$product_id]->inout;
            $balance[$product_id]->prev_month_created_at  = $balance_previous_month[$product_id]->created_at;

            $balance[$product_id]->inPackageCount = isset($inProducts[$product_id]) ? $inProducts[$product_id]->packageCount: 0;
            $balance[$product_id]->inCount = isset($inProducts[$product_id]) ? $inProducts[$product_id]->count:0;
            $balance[$product_id]->inPrice = isset($inProducts[$product_id]) ? $inProducts[$product_id]->price:0;
            $balance[$product_id]->inDollar_rate = isset($inProducts[$product_id]) ? $inProducts[$product_id]->dollar_rate:0;

            $balance[$product_id]->outPackageCount = isset($outProducts[$product_id]) ? $outProducts[$product_id]->packageCount: 0;
            $balance[$product_id]->outCount = isset($outProducts[$product_id]) ? $outProducts[$product_id]->count:0;
            $balance[$product_id]->outPrice = isset($outProducts[$product_id]) ? $outProducts[$product_id]->price:0;
            $balance[$product_id]->outDollar_rate = isset($outProducts[$product_id]) ? $outProducts[$product_id]->dollar_rate:0;


        }


        return $balance;


    }

    public function getInOutProductsByDate($inout = 1, $startDate = null, $endDate = null)
    {
        $res = DB::table("inout_sets as i")
            ->selectRaw("rmd.product_id, SUM(rmd.packageCount) AS packageCount, SUM(rmd.count) AS count, AVG(rmd.price) as price,
            rm.dollar_rate")
            ->leftJoin("raw_material_details as rmd", "rmd.set_id", "=", "i.id")
            ->leftJoin("raw_materials as rm", "rm.id", "=", "rmd.raw_material_id")
            ->whereRaw("DATE(i.created_at) >= ?", ['2022-08-01'])
            ->whereRaw("DATE(i.created_at) < ?", ['2022-09-01'])
            ->where("i.inout", "=", $inout)
            ->groupBy("rmd.product_id")
            ->get();

        $inout = array();
        foreach ($res as $product) {
            $inout[$product->product_id] = $product;
        }

        return $inout;
    }

    public function getBalance($date)
    {
        $res = DB::select(
            DB::raw("SELECT p.id, p.`name`, p.measurement, p.code, p.country,
            rm.price, rm.dollar_rate, rm.currency_id, rm.count, rm.packageCount,
            rm.set_name, rm.inout, rm.created_at
            FROM products p
            LEFT JOIN (
                SELECT p.id, p.code, p.country, p.measurement
                , rm.price, rm.dollar_rate, rm.currency_id
                , SUM(rmd.count) AS count, sum(rmd.packageCount) AS packageCount, rmd.set_name, rmd.`inout`, rmd.created_at
                FROM products p
                LEFT JOIN raw_materials rm ON rm.product_id = p.id
                LEFT JOIN raw_material_details rmd ON rmd.raw_material_id = rm.id
                LEFT JOIN inout_sets i ON i.id = rm.set_id
                WHERE DATE(i.created_at) <= ? AND i.`inout` = 1
                GROUP BY p.id
            ) rm ON rm.id = p.id"), [$date]
        );


        $raw_materials = array();
        foreach ($res as $product) {
            $raw_materials[$product->id] = $product;
        }

        return $raw_materials;

    }





}
