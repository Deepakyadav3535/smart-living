<?php
include_once('config.html');
try{
 $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB.";charset=".DB_CHARSET,DB_USER,DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    error("DB_ERROR","Database Connection failed->" . $e->getMessage());
    die();
}

function db_insert($data=array()){
	global $pdo;
	$table=null;
	$values=array();
	$primary_key=false;
	$column_name=false;
	$default='';
	extract((array)$data);
	try{
    	$columnString = implode(',', array_keys((array)$values));
		$valueString = implode(',', array_fill(0, count((array)$values), '?'));

		$query="INSERT INTO $table ({$columnString}) VALUES ({$valueString})";
		$stmt = $pdo->prepare($query);
         //print_r($query); die();
		$stmt->execute(array_values((array)$values));
       
        
		if($primary_key && $stmt->rowCount()){
			return db_last_id($data,$default);
		}elseif($stmt->rowCount()){
			return true;
		}else{
			return false;
		}
    }
	catch(PDOException $e)
    {
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_update($data=array()){
	global $pdo;
	$table=null;
	$values=array();
	$where=array();
	extract((array)$data);
	try{
		$valueArray = array();
    	foreach ((array)$values as $key => $value) {
    		$valueArray[]=$key.'=?';
    	}
		$valueString = implode(',',$valueArray);
		
		$whereArray = array();
		foreach ((array)$where as $key => $value){
    		$whereArray[]=$key.'=?';
    	}
		$whereString = implode(' AND ',$whereArray);

		$query="UPDATE $table SET {$valueString} WHERE {$whereString}";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array_merge(array_values((array)$values),array_values((array)$where)));
		if($stmt->rowCount()){
			return true;
		}else{
			return false;
		}
    }
	catch(PDOException $e)
    {
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_delete($data=array()){
	global $pdo;
	$table='';
	$where=array();
	extract((array)$data);
	try{
		$whereArray = array();
		foreach ((array)$where as $key => $value) {
    		$whereArray[]=$key.'=?';
    	}
		$whereString = implode(' AND ',$whereArray);

		$query="DELETE FROM $table WHERE {$whereString}";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array_values((array)$where));
		if($stmt->rowCount()){
			return true;
		}else{
			return false;
		}
    }catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_select($data=array()){
	global $pdo;
	$table='';
	$where=array();
	$like=array();
	$in=array();
	$not_in=array();
	$between=array();
	$column_name='*';
	$WHERE_ARRAY=array('1');
	$WHERE='';
	$EXTRA='';
	extract((array)$data);
	try{
		$whereArray = array();
		db_join_array($WHERE_ARRAY,$where);
		db_join_array($WHERE_ARRAY,$like);
		db_join_array($WHERE_ARRAY,$in);
		db_join_array($WHERE_ARRAY,$not_in);
		db_join_array($WHERE_ARRAY,$between);
		
		$WHERE=" WHERE ".implode(' AND ',$WHERE_ARRAY);
		$column_name=implode(',',(array)$column_name);
		if(isset($order_by)){
			$EXTRA.=" ORDER BY ".implode(', ',(array)$order_by);
		}
		
		if(isset($group_by)){
			$EXTRA.=" GROUP BY ".implode(', ',(array)$group_by);
		}

		if(isset($limit)){
			$EXTRA.=" LIMIT ";
			if(isset($offset)){	
				$EXTRA.=" $offset,";
			}
			$EXTRA.="$limit ";
		}	
		
		$query="SELECT $column_name FROM $table  $WHERE $EXTRA";
		$stmt = $pdo->prepare($query);
    	$stmt-> execute(
	    			array_merge(
	    					array_values((array)$where),
	    					array_values((array)$like),
	    					array_values((array)$in),
	    					array_values((array)$not_in),
	    					array_values((array)$between)
	    			)
	    		);
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
    {
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return array();
    }
}


function db_is_exist($data=array()){
	global $pdo;
	$table='';
	$where=array();
	$WHERE_ARRAY=array('1');
	$WHERE='';
	extract((array)$data);
	try{
		$whereArray = array();
		db_join_array($WHERE_ARRAY,$where);
		
		$WHERE=" WHERE ".implode(' AND ',$WHERE_ARRAY);
		
		$query="SELECT * FROM $table $WHERE";
		
		$stmt = $pdo->prepare($query);
    	$stmt-> execute(array_values((array)$where));
		if($stmt->rowCount()){
			return true;
		}else{
			return false;
		}
	}
	catch(PDOException $e)
    {
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}
	

//fetch last inserted records
function db_last_id($data,$default=''){	
	global $pdo;
	$column_name=false;
	$primary_key=false;
	$table=null;
	$where=array();
	$WHERE='';
	$WHERE_ARRAY=array('1');
	$is_array=false;
	extract((array)$data);
	if(is_array($column_name)){ $is_array=true;}
	try{
		$whereArray = array();
		db_join_array($WHERE_ARRAY,$where);
		
		$WHERE=" WHERE ".implode(' AND ',$WHERE_ARRAY);
		if(!$column_name){
			$column_name=$primary_key;
		}
		$primary_key=implode(', ',(array)$primary_key);	
		$column_name=implode(', ',(array)$column_name);	
		
		$query="SELECT $column_name FROM $table $WHERE ORDER by $primary_key DESC LIMIT 1 ";
		
		$stmt = $pdo->prepare($query);
    	$stmt-> execute(array_values((array)$where));
		if(($is_array || strpos($column_name, ',')) && $stmt->rowCount()){
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}elseif($stmt->rowCount()){
			return $stmt->fetch(PDO::FETCH_ASSOC)[$column_name];
		}else{
			return $default;
		}
	}catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return $default;
    }
}

function db_first_id($data,$default=''){	
	global $pdo;
	$column_name=false;
	$primary_key=false;
	$table=null;
	$where=array();
	$WHERE='';
	$WHERE_ARRAY=array('1');
	$is_array=false;
	extract((array)$data);
	if(is_array($column_name)){ $is_array=true;}
	try{
		$whereArray = array();
		db_join_array($WHERE_ARRAY,$where);
		
		$WHERE=" WHERE ".implode(' AND ',$WHERE_ARRAY);
		if(!$column_name){
			$column_name=$primary_key;
		}
		$primary_key=implode(', ',(array)$primary_key);	
		$column_name=implode(', ',(array)$column_name);	
		
		$query="SELECT $column_name FROM $table $WHERE ORDER by $primary_key ASC LIMIT 1 ";
		
		$stmt = $pdo->prepare($query);
    	$stmt-> execute(array_values((array)$where));
		if(($is_array || strpos($column_name, ',')) && $stmt->rowCount()){
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}elseif($stmt->rowCount()){
			return $stmt->fetch(PDO::FETCH_ASSOC)[$column_name];
		}else{
			return $default;
		}
	}catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return $default;
    }
}
	


function db_select_query($query='',$params=array()){
	global $pdo;
	try{
		$stmt = $pdo->prepare($query);
    	$stmt-> execute(array_values((array)$params));
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return array();
    }
}

function db_insert_query($query='',$params=array()){
	global $pdo;
	try{
    	$stmt = $pdo->prepare($query);
		$stmt->execute(array_values((array)$params));
		if($stmt->rowCount()){
			return true;
		}
		return false;
    }catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_update_query($query='',$params=array()){
	global $pdo;
	try{
    	$stmt = $pdo->prepare($query);
		$stmt->execute(array_values((array)$params));
		if($stmt->rowCount()){
			return true;
		}
		return false;
    }catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_delet_query($query='',$params=array()){
	global $pdo;
	try{
    	$stmt = $pdo->prepare($query);
		$stmt->execute(array_values((array)$params));
		if($stmt->rowCount()){
			return true;
		}
		return false;
    }catch(PDOException $e){
    	$error="</br>error:".$e->getMessage();
    	$error.="</br>query:".$query;
    	error("DB_ERROR",$error);
    	return false;
    }
}

function db_join_array(&$main_array,$array){
	$stringArray = array();
	foreach((array)$array as $key => $value) {
    		$stringArray[]=$key.'=?';
	}
	if(!empty($stringArray)){
		$main_array[] = implode(' AND ',$stringArray);
	}
}
?>