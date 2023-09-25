<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsNotificationsController extends Controller
{

    public function getMessages() {
        $messages =  DB::table("clients_notifications")
        ->orderBy("created_at", "desc")
        ->get();

        return $messages;

    }

    public function addMessage($title, $message){
        $notification = ClientsNotification::create([
            "title" => $title,
            "message" => $message
        ]);

        return $notification;
    }

    public function deleteMessage(Request $request)
    {
        ClientsNotification::where("id", $request->id)->delete();

        return $request;
    }

    public function sendPushNotification(Request $request)
    {
        $SERVER_API_KEY = 'AAAAnYGlHts:APA91bHQDz-M21vVx66bxtatHrUcmCn-1mQvuBdVbPBo7HCILJIfsKvg2h_J2Qq173EBk4D35EXa0ODSYfZHGUPpTt9S0gM1vsk3SQhONGrFevjd8b2q0Yhif536I_olSUkMR2u7lzQ0';
        $firebaseToken = Client::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->message,
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

        $notification = $this->addMessage($request->title, $request->message);

        return $notification;


    }
}
