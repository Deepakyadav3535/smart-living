<?php
function send_sms($mobiles='',$message=''){

    $message = urlencode($message);
    if(empty($mobiles)){
        error('mobile can not be empty');
    }
    elseif(empty($message)){
        error('message can not be empty');
    }else{
        
        $curl = curl_init();
        $url="http://www.bulksmsc.com/api/sendhttp.html?authkey=".SMS_KEY."&mobiles=$mobiles&message=$message&sender=".SMS_SENDER."&route=4&country=91&unicode=1&response=json&campaign=API";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($curl);
        
        if($data=@json_decode($res,true)){
        	if($data['type']=='success'){
        		return true;
        	}
        }
        return false;
    }
}
?>