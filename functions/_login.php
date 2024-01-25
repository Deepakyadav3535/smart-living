<?php
function is_user_login() {
	if(isset($_SESSION['login']) && $_SESSION['login']==true && isset($_SESSION['login_id']) && isset($_SESSION['login_type']) && $_SESSION['login_type']=='user'){
		return true;
	}else{
		return false;
	}	
}
	
function is_admin_login() {
	if(isset($_SESSION['login']) && $_SESSION['login']==true && isset($_SESSION['login_id']) && isset($_SESSION['login_type']) && $_SESSION['login_type']=='admin'){
		return true;
	}else{
		return false;
	}	
}

?>