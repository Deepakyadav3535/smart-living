<?php

function send_email($to,$subject,$template,$data=array()){
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From:".SENDER_NAME." <".SENDER_EMAIL.">" . "\r\n";
    $message=get_template($template,$data);
    $mail=@mail($to, $subject, $message, $headers);
    return $mail?true:false;
}

function get_template($template,$data=array()){
    try{
        ob_start();
        extract($data);
        include $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }catch(Exception $e){
        error("EMAIL_ERROR",$e->getMessage());
    }    
}
?>