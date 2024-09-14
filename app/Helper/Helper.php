<?php

namespace App\Helper;

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Helper
{

    public $smsKey = 'SYnYjh4fZ58-is7IBxkmj78JUvS1z7jWlIeQxveth3';
    public $fair_base_server_key = "AAAAN8dBdoo:APA91bFVXXbE-m8kkoUb2X0EmBjyfvHB0NCpfR8B1MoBjYaZZwi60W_gRZIBXojEgwRj7v1xNWlceJsqT3ClxtcZmDMdwXFcQULcpiovH2FVXGXMyfeIHEwE2LYtS_7d1aJxqKFn_EO2";

    public static function notification($userid, $user_name, $title, $message, $type = '', $fcmToken)
    {

        $fire_base_token = "AAAAN8dBdoo:APA91bFVXXbE-m8kkoUb2X0EmBjyfvHB0NCpfR8B1MoBjYaZZwi60W_gRZIBXojEgwRj7v1xNWlceJsqT3ClxtcZmDMdwXFcQULcpiovH2FVXGXMyfeIHEwE2LYtS_7d1aJxqKFn_EO2";

        $device_token = $fcmToken;
        $user_id = $userid;
        $username = $user_name;
        $title_m = $title;
        $push_message = $message;
        $ty = $type;
        $msg_type = "";
        $request_id = "";
        $admin = "";
        $img = "";

        $check = self::sendNotification($user_id, $push_message, $msg_type, $request_id, $username, $admin, $img, $device_token, $fire_base_token);

        DB::table('notifications')->insert([
            "user_id" => $user_id,
            "title" => $title_m,
            "message" => $push_message,
            "type" => $ty
        ]);


        //dd($check);

        return $check;
    }

    public static function sendNotification($user_id = '', $push_message = '', $msg_type = "", $request_id = "", $username = "", $admin = "", $img = "", $device_token = '', $fire_base_token = '')
    {

        $surlData = 'curl -X POST --header "Authorization: key=' . $fire_base_token .
            '" --header "Content-Type: application/json" https://fcm.googleapis.com/fcm/send -d "{\"to\":\"' . $device_token .
            '\",\"priority\":\"high\",\"data\":{\"msg_type\":\"' . $msg_type .
            '\",\"request_id\":\"' . $request_id .
            '\",\"image_url\":\"' . $img .
            '\",\"user_name\":\"' . $username .
            '\",\"msg\":\"' . $push_message .
            '\"},\"notification\":{\"body\": \"' . stripslashes($push_message) .
            '\",\"title\":\"' . $msg_type .
            '\",\"image\":\"' . $img . '\"}}"';

        $res = exec($surlData);

        return $res;

        // $res = shell_exec('curl -X POST --header "Authorization: key='.$fire_base_token.'" --header "Content-Type: application/json" https://fcm.googleapis.com/fcm/send -d "{\"to\":\"'.$device_token.'\",\"priority\":\"high\",\"data\":{\"msg_type\":\"'.$msg_type.'\",\"request_id\":\"'.$request_id.'\",\"image_url\":\"'.$img.'\",\"user_name\":\"'.$username.'\",\"msg\":\"'.$push_message.'\"},\"notification\":{\"body\": \"'.stripslashes($push_message).'\",\"title\":\"'.$msg_type.'\",\"image\":\"'.$img.'\"}}"');
        //print_r($res); 
    }

    public static function discount($oldPrice, $newPrice)
    {

        if ($oldPrice > 0) {
            $percentChange = ($oldPrice / $newPrice) * 100;
            echo round($percentChange - 100) . "%";
        }
    }


    public static function tax_included($id)
    {
        $result = 0;
        $hasData = DB::table('services')->where('id', $id)->first();
        if (!empty($hasData)) {
            $hasTaxPAy = DB::table('tax_pay')->where('id', $hasData->product_tax)->first();
            if (!empty($hasTaxPAy)) {
                $result = $hasTaxPAy->value;
            }
        }
        return $result;
    }

    public static function tax_excluded($id, $total)
    {
        $result = 0;
        $hasData = DB::table('services')->where('id', $id)->first();
        if (!empty($hasData)) {
            $hasTaxPAy = DB::table('tax_pay')->where('id', $hasData->product_tax)->first();
            if (!empty($hasTaxPAy)) {

                $cal = $total * $hasTaxPAy->value / 100;
                $result = [
                    "value" => $hasTaxPAy->value,
                    "amount" => round($cal)
                ];
            }
        }
        return $result;
    }


    public function sentSMS($number, $message)
    {
        $apiKey = urlencode($this->smsKey);
        $numbers = array($number);
        $sender = urlencode('TXTLCL');
        $message = rawurlencode($message);
        $numbers = implode(',', $numbers);

        $data = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function gift_wrap()
    {

        return 0;
    }
}
