<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web_notification{  
    private static $is_background = "TRUE";
    public function __construct() {     
     
    }
    public function sendPushNotificationToFCMSever($token, $message, $notifyID, $title,$order_id,$icon,$redirect_url) {

        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $sound = base_url().'assets/audio/store_door.mp3';
       $image = base_url().'upload/drivers/'.$icon;
        $url_redirect = $redirect_url.$order_id;
       // echo $token;die;
        $fields = array(
            'to' => $token,
            'priority' => 10,
            'notification' => array('title' => $title, 'body' =>  $message ,'sound'=>$sound, 'icon'=>$image,'click_action'=>$url_redirect),
        );
        
      //print_r(json_encode($fields));die;
        $headers = array(
              'Authorization:key='.USER_WEB_FIREBASE_API,
              'Content-Type: application/json'
           );  
         
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
                // Close connection      
        curl_close($ch);
          $result;
    }


}