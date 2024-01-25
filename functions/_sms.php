<?php
function send_free_sms($mobiles='',$message=''){
    $host='';
    $message = urlencode(substr($message, 0, 140));
    if(empty($mobiles)){
        error('mobile can not be empty');
    }
    elseif(empty($message)){
        error('message can not be empty');
    }else{
        $curl = curl_init();
        $url="http://way2sms.com/";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($curl);
        if (preg_match('#Location: (.*)#', $res, $r)){
            $host = trim($r[1]);
        }

        curl_setopt($curl, CURLOPT_URL, $host . "Login1.action");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "username=".urlencode(SMS_ID)."&password=".urlencode(SMS_PASSWORD)."&button=Login");
        curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
        curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie_way2sms");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_REFERER, $host);
        $text = curl_exec($curl);
    
         // Check if any error occured
        if (curl_errno($curl)){
            error("access error : " . curl_error($curl));
        }

        // Check for proper login
        $pos = stripos(curl_getinfo($curl, CURLINFO_EFFECTIVE_URL), "main.action");
        if ($pos === "FALSE" || $pos == 0 || $pos == ""){
            error('Invalid SMS ID/PASSWORD');
        }

        $refurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        
        // Extract the token from the URL
        $tokenLocation = strpos($refurl, "Token");
        $token = substr($refurl, $tokenLocation + 6, 37);
        
        foreach ((array)explode(',',$mobiles) as $key => $mobile) {
            curl_setopt($curl, CURLOPT_URL, $host . 'smstoss.action');
            curl_setopt($curl, CURLOPT_REFERER, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "ssaction=ss&Token=" . $token . "&mobile=" . $mobile . "&message=" . $message . "&button=Login");
            $contents = curl_exec($curl);
            # code...
        }
        curl_close($curl);

            //Check Message Status
        $pos = strpos($contents, 'Message has been submitted successfully');
        $res = ($pos !== false) ? true : false;
        return $res?true:false;
    }
}
?>