<?php
include_once('config.html');

$root_path=ROOT_PATH;

require_once $root_path.'functions/lib/streams.html';
require_once $root_path.'functions/lib/gettext.html';

$get_lang=isset($_REQUEST['lang'])?$_REQUEST['lang']:'';

if(isset($_COOKIE["lang"])){
    $cookie_value = $_COOKIE["lang"];
	if($get_lang){
		$cookie_value = $get_lang;
	}
}else{
	$cookie_value = "en_US";
} 

$locle_lang=$cookie_value;

$lang_file=$root_path."functions/locale/$locle_lang.mo";

$locale_file = new FileReader($lang_file);
$locale_fetch = new gettext_reader($locale_file);

	function lang($text){
		global $locale_fetch;
		return $locale_fetch->translate($text);
	}

?>
