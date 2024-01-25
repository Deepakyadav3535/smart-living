<?php
function send_notification($data=array()){
    $body='This body of notification';
    $title='Title of notification';
    $icon='myIcon';/*Default Icon*/
    $sound='mySound';/*Default sound*/
    $fcm_id='';
    $extra=array();
    extract($data);
        
    $notification = array(
        'body'  => $body,
        'title' => $title,
        'icon'  => $icon,/*Default Icon*/
        'sound' => $sound/*Default sound*/
    );
    
    $fields = array(
        'to'        => $fcm_id,
        'notification'  => $notification,
        'data'  => $extra
    );

    $headers = array(
            'Authorization: key=' . FCM_SERVER_KEY,
            'Content-Type: application/json'
    );
        
        #Send Reponse To FireBase Server    
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    return $result;
}
function notification_error($error){
	if(DEBUG_MODE){
		$backtrace = debug_backtrace();
  		$caller = end($backtrace);
		echo "</br><b>FILE:</b>".$caller['file'];
  		echo "</br><b>LINE:</b>".$caller['line'];
  		echo  "</br><b>email_error:</b>" . $error;
  	}
}
?>