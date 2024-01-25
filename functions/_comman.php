<?php
	function generate_random_string($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++){
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	function generate_otp($length = 4) {	
		$result = '';
		for($i = 0; $i < $length; $i++){
			$result .= mt_rand(0, 9);
		}
		return $result;
	}
	
	function money($money) {
		return number_format ($money ,2,"." ,",");
	}
	
	
	function in_array_2d($needle, $array, $strict = false) {
		foreach ($array as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}
	
	function is_current_page($page='') {
		if(in_array(basename($_SERVER['PHP_SELF'],'.html'),explode(',',implode(',', (array)$page)))){
			return true;
		}
		else{
			return false;
		}
	}
	
	
	function redirect($page,$data=array(),$time=0) {
		if(!empty($data) && is_array($data)){
			foreach($data as $key=>$val){
				$_SESSION["$key"]=$val;
			}
		}
		
		echo "<script>setTimeout(function(){location.href='$page';},$time);</script>";
		$seconds=$time/1000;
		header( "Refresh:$seconds;Url=$page" );
		exit(0);
		die;
	}
	
	
	function date_formate($date=null,$format='Y-m-d',$extra='+0 day') {
		return date("$format",strtotime($extra,strtotime($date)));
	}
	
	
	function grab_image($url,$saveto) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
		$rawdata = curl_exec($ch);
		$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch) ;
		
		$fp = fopen($saveto,'w');
		fwrite($fp, $rawdata); 
		fclose($fp);             
	}

	// *******RETURN ALL DATES BETWEEN TWO DATES*******

	function returnDates($fromdate, $todate) {
	   	$fromdate = \DateTime::createFromFormat('d/m/Y', $fromdate);
	   	$todate = \DateTime::createFromFormat('d/m/Y', $todate);
	   	return new \DatePeriod(
	       $fromdate,
	       new \DateInterval('P1D'),
	       $todate->modify('+1 day')
	   	);
	}

	function getParent($name,$parents=array()) 
	{
	    $query = "SELECT * FROM members WHERE name ='$name'";
	    $result = db_select_query($query);
	    if(count($result)){
	    	$parents[]=$result[0]['parent_id'];
	    	return getParent($result[0]['parent_id'],$parents);
	    }else{
	    	return $parents;
	    }
	    
	}
		function limitTextWords($content = true, $limit = false, $stripTags = true, $ellipsis = true) 
	{
	   if($content && $limit) 
	   {
	      $content = ($stripTags ? strip_tags($content) : $content);
	      $content = explode(' ', $content, $limit+1);
	      array_pop($content);
	      if ($ellipsis) 
	      {
	         array_push($content, '');
	      }
	      $content = implode(' ', $content);
	   }
	   return $content;
	}

	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function get_month_by_num($current_month){
		if($current_month==1){
		$month="jan";
		}elseif($current_month==2){
			$month="feb";
		}elseif($current_month==3){
			$month="mar";
		}elseif($current_month==4){
			$month="apr";
		}elseif($current_month==5){
			$month="may";
		}elseif($current_month==6){
			$month="june";
		}elseif($current_month==7){
			$month="july";
		}elseif($current_month==8){
			$month="aug";
		}elseif($current_month==9){
			$month="sept";
		}elseif($current_month==10){
			$month="oct";
		}elseif($current_month==11){
			$month="nov";
		}elseif($current_month==12){
			$month="decem";
		}else{
			$month="mar";
		}
			return $month;
	}
	
?>