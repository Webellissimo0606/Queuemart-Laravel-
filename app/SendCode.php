<?php

namespace App;
use DB;
use App\NexmoPrice;

class SendCode
{
    // public static function sendCode($phone){

    //     $code = rand(1111, 9999);

    //     $fields=[
    //         "from=+79853038670",
    //         "text=".'Verify Code: '. $code,
    //         "to=".$phone,
    //         "api_key=693217d4",
    //         "api_secret=Z5dAh0BvnFCreVwO",
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL,"https://rest.nexmo.com/sms/json");
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS,implode("&",$fields));

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $server_output = curl_exec($ch);

    //     curl_close ($ch);

    //     // Further processing ...

    //     return $code;
    // }

    public static function sendSMS($phone, $title, $content){

        $INSTANCE_ID = 18;  // TODO: Replace it with your gateway instance ID here
        $CLIENT_ID = "krichardtai@gmail.com";  // TODO: Replace it with your Forever Green client ID here
        $CLIENT_SECRET = "eadc549b33144ce8a9bfccc1fa6494ec";   // TODO: Replace it with your Forever Green client secret here
        $postData = array(
          'number' => $phone,  // TODO: Specify the recipient's number here. NOT the gateway number
          'message' => $title.'
'.$content
        );
        $headers = array(
          'Content-Type: application/json',
          'X-WM-CLIENT-ID: '.$CLIENT_ID,
          'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
        );
        $url = 'http://api.whatsmate.net/v3/whatsapp/single/text/message/' . $INSTANCE_ID;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_exec($ch);
        // echo "Response: ".$response;
        curl_close($ch);
        return;
    }

    public static function sendCode($phone){

        $basic = new \Nexmo\Client\Credentials\Basic('693217d4', 'Z5dAh0BvnFCreVwO');
        $client = new \Nexmo\Client($basic);
        $code = rand(1111, 9999);
        $message = $client->message()->send([
            'to'    =>  $phone,
            'from'  =>  'queuemart',
            'text'  =>  'Verify Code: '. $code,
        ]);
        // $message['remaining-balance'];
        $input['balance'] = $message['remaining-balance'];
        $input['price'] = $message['message-price'];
        $input['status'] = $message['status'];
        NexmoPrice::create($input);
        return $code;
    }
}



// $fields=[
//             "from=+79853038670",
//             "text=". $title . ': ' . $content . 'https://queuemart.me/',
//             "to=".$phone,
//             "api_key=693217d4",
//             "api_secret=Z5dAh0BvnFCreVwO",
//         ];

//         $ch = curl_init();

//         curl_setopt($ch, CURLOPT_URL,"https://rest.nexmo.com/sms/json");
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_POSTFIELDS,implode("&",$fields));

//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//         $server_output = curl_exec($ch);

//         curl_close ($ch);

        // Further processing ...