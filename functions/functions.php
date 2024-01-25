<?php
include_once('config.html');
/* include_once('_logs.html');
include_once('_database.html');
include_once('_files.html');
include_once('_comman.html');
include_once('_login.html');
include_once('_email.html');
include_once('_sms2.html');
include_once('_notification.html');
include_once('_api.html'); */
// include_once('_lang.html');



if(isset($_REQUEST['g-recaptcha-response'])){
	unset($_REQUEST['g-recaptcha-response']);
}

if(isset($_REQUEST['hiddenRecaptcha'])){
	unset($_REQUEST['hiddenRecaptcha']);
}

if(isset($_REQUEST['form_botcheck'])){
	unset($_REQUEST['form_botcheck']);
}

if(isset($_REQUEST['PHPSESSID'])){
	unset($_REQUEST['PHPSESSID']);
}

if(isset($_POST['PHPSESSID'])){
	unset($_POST['PHPSESSID']);
}

if(isset($_REQUEST['timezone'])){
	unset($_REQUEST['timezone']);
}
if(isset($_POST['timezone'])){
	unset($_POST['timezone']);
} 

if(isset($_REQUEST['cpsession'])){
	unset($_REQUEST['cpsession']);
}

if(isset($_POST['cpsession'])){
	unset($_POST['cpsession']);
}  

$cookie_name = "session_id";
$cookie_value = time();
if(!isset($_COOKIE[$cookie_name])) {
  setcookie($cookie_name, $cookie_value, time() + (86400 * 300), "/"); // 86400 = 1 day
  $session_id=$cookie_value;
} else {
 $session_id=$_COOKIE[$cookie_name];
}

define('SESSION_ID',$session_id);

?>
