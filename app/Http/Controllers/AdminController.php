<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\Client;

class AdminController extends Controller
{
    public function testsmssend()
    {
        // $firebaseToken = "cA6Gc7pzRGyCn7UVkWPN9V:APA91bFJ35EHAaeQAh8c1EZc1PQiGIBHWQ-LZM64qefZ_ubC28EG-DYrOVZzex4MmLhnaHFtnm8Vu20hvKB_E8y7vWi8HBHwHNO1DjXn76xQnwN8AGoYlk0g0RI0h8WJjppBQvdLZEhN";
        // $title = "Mening Testim";
        // $message = "Sozlamalar";
        // $SERVER_API_KEY = 'AAAAnYGlHts:APA91bHQDz-M21vVx66bxtatHrUcmCn-1mQvuBdVbPBo7HCILJIfsKvg2h_J2Qq173EBk4D35EXa0ODSYfZHGUPpTt9S0gM1vsk3SQhONGrFevjd8b2q0Yhif536I_olSUkMR2u7lzQ0';
        // // $firebaseToken = Client::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        // $data = [
        //     "registration_ids" => [$firebaseToken],
        //     "notification" => [
        //         "title" => $title,
        //         "body" => $message,
        //     ]
        // ];
        // $dataString = json_encode($data);

        // $headers = [
        //     'Authorization: Bearer ' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);

        // return $response;

    }


    // public function addTag(Request $request)
    // {
    //     $this->validate($request, [
    //         'tagName' => "required"
    //     ]);

    //     return Tags::create([
    //         'tagName' => $request->tagName
    //     ]);
    // }

    // public function getTags()
    // {
    //     return Tags::orderBy("id", 'desc')->get();
    // }

    // public function editTag(Request $request)
    // {
    //     $this->validate($request, [
    //         'id'        => "required",
    //         'tagName'   => "required"
    //     ]);

    //     Tags::where('id', $request->id)->update([
    //         'tagName' => $request->tagName
    //     ]);

    //     return response()->json(['tagName' => $request->tagName]);
    // }

    // public function deleteTag(Request $request)
    // {
    //     $this->validate($request, [
    //         'id'        => "required",
    //     ]);

    //     return Tags::where('id', $request->id)->delete();
    // }

    public function sendPushNotification(Request $request)
    {
        $firebaseToken = Client::whereNotNull('fcm_token')->pluck('fcm_token')->all();

        $SERVER_API_KEY = 'AAAAnYGlHts:APA91bHQDz-M21vVx66bxtatHrUcmCn-1mQvuBdVbPBo7HCILJIfsKvg2h_J2Qq173EBk4D35EXa0ODSYfZHGUPpTt9S0gM1vsk3SQhONGrFevjd8b2q0Yhif536I_olSUkMR2u7lzQ0';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "My Title",
                "body" => "My message body",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: Bearer ' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }
}
