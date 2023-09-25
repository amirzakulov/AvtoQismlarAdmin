<?php
namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use stdClass;

class ApiAuthController extends Controller
{

    public function register(Request $request)
    {
        $user = array(
            'fullName'  => $request->fullName,
            "phone"     => $request->phone,
            "address"   => $request->address,
            "fcmToken"  => $request->fcmToken,
            "deviceName" => $request->deviceName,
        );
        $errors = false;
        $phoneExist = false;

        $rules = [
            'fullName'  => 'required',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        ];

        //custom validation message
        $messages = [
            'fullName.required' => 'Iltimos, Ismingizni kiriting!',
            'phone.required'=> 'Iltimos, telefon raqamingizni kiriting!',
            'phone.regex'  => 'Telefon raqamini tekshirib qayta kiriting!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $errors = true;
        }

        //Agar telefon raqam bor bulsa va u tasdiqlangan bulsa (verified=1)
        if(!$errors) {

            $rules      = ['phone'          => 'unique:clients,phone,NULL,id,verified,1'];
            $messages   = ['phone.unique'   => "Bunday telefon raqam tizimda mavjud! \nTekshirib qayta kiriting!"];

            $validator = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()) {
                $errors = true;
                $phoneExist = true;
            }

            //Agar telefon raqam bor bulsa va u tasdiqlanmagan bulsa (verified = 0)
            $rules      = ['phone' => 'unique:clients,phone,NULL,id,verified,0'];
            $validator  = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) {

                $phoneExist = false;
                $code = rand(100000, 999999);
                Client::where('phone', $request->phone)->update(['code' => $code]);
                $this->sendSms($request->phone, $code);
                return response()->json([
                    'phoneExist'    => $phoneExist,
                    'errors'        => $errors,
                    'errorMessages' => $validator->errors()->all(),
                    'user'          => $user
                ]);
            }
        }

        //Agar xech qanday xatoliklar bulmasa
        if(!$errors) {
            $code = rand(100000, 999999);
            $client = Client::create([
                'fullName'      => $request->fullName,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'code'          => $code,
                'fcm_token'     => $request->fcmToken,
                'deviceName'    => $request->deviceName,
                'verified'      => 0,
            ]);

            $user["client_id"] = $client->id;

            if($client) {
                $this->sendSms($request->phone, $code);
            }
        } else {
            $rules      = ['phone' => 'unique:clients,phone,NULL,id,verified,1'];
            $validator  = Validator::make($request->all(), $rules, $messages);
        }

        return response()->json([
            'phoneExist'    => $phoneExist,
            'errors'        => $errors,
            'errorMessages' => $validator->errors()->all(),
            'user'          => $user
        ]);
    }

    public function checkRegistrationCode(Request $request)
    {
        $data = [
            "codeVerified" => false,
            "fullName" => "",
            "phone" => "",
            "address" => "",
            "token" => "",
            "token_type" => "",
        ];

        $client = Client::where('phone', $request->phone)
            ->where('code', $request->code)
            ->first();

        if($client) {
            //Agar user code tugri bulsa, codeni null ga update qilib quyamiz
            Client::where('phone', $request->phone)
                ->where('code', $request->code)
                ->update(['code' => null, "verified" => 1]);


            $token = $client->createToken($request->deviceName)->plainTextToken;
            if($client) {
                $data["codeVerified"]    = true;
                $data["client_id"]       = $client->id;
                $data["fullName"]        = $client->fullName;
                $data["phone"]           = $client->phone;
                $data["address"]         = $client->address;
                $data["token"]           = $token;
                $data["token_type"]      = "Bearer";
            }
        }

        return response()->json($data);
    }

    public function checkUserPhone(Request $request){
        $client = Client::where('phone', $request->phone)->where('verified', 1)->first();

        if($client) {
            $code = rand(100000, 999999);

            Client::where('phone', $request->phone)->update(['code' => $code]);
            $this->sendSms($request->phone, $code);

            return response()->json([
                'isPhoneRegistered' => true,
                'errorMessage'     => "",
            ]);
        } else {
            return response()->json([
                'isPhoneRegistered' => false,
                'errorMessage'     => "Bunday telefon raqam tizimda mavjud emas! \nTekshirib qayta kiriting!",
            ]);
        }
    }

    private function sendSms($phone, $code)
    {
        if (substr($phone, 0, 1)=="+") $phone = substr($phone, 1, strlen($phone) - 1);
        $text  = 'Kod: '.$code.' Dasturga kirish uchun kodni tering';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'notify.eskiz.uz/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email' => 'test@eskiz.uz','password' => 'j6DWtQjjpLDNjWEk74Sx'),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (!$response) return false;

        $result = $response;
        $result = json_decode($result, true);

        if ($result["message"] != "token_generated") return false;

        $token = $result["data"]["token"];


        $curl = curl_init();

        $authorization = "Authorization: Bearer ". $token;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'notify.eskiz.uz/api/message/sms/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('mobile_phone' => $phone,'message' => $text,'from' => '4546','callback_url' => 'http://0000.uz/test.php'),
            CURLOPT_HTTPHEADER => array($authorization),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

}
