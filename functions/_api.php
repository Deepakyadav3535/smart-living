<?php
function api_start($url=''){
	define('API_URL',$url);
	echo "
		<style>
		.tag{
		   list-style: none;
		}
		.tag:before{
		   content: '';
		   display: inline-block;
		   height: 10;
		   width: 10;
		   background-image: url('http://www.clker.com/cliparts/9/1/6/c/12456876221083093551Soeb_Plain_Arrow_3.svg.hi.png');
		   float:left;
		   margin: 4px 10px;
		}
		</style>
		<p><b>(f)</b>:file </p>
		<p><b>(o)</b>:optional parameters</p>
		<ul>
		   	<li>
		      <p><b>BASE URL</b>:$url</p>
		   	</li>
		";
}
function api_tag_start($html){
	echo "<li class='tag'><p><b>$html</b></p><ul>";
}
function api_route($route='',$params=array(),$description){
	$keys = array_keys($params);
	$result = preg_grep('/\w+[(\(f\))]\w+/', $params);
	$params_link = array_diff($params, $result);
	$params_link=implode('=?&', $params_link);
	if($params_link){
		$params_link.="=?";
	}
	$link=API_URL."/".$route.'?'.$params_link;
	echo "<li>
            <b>$route</b>
            <ul>
               <li>description:$description</li>
               <li>parameters:".json_encode($params)."</li>      
               <li>link:<a href='$link'>".$link."</a></li>      
            </ul>
         </li>";
}
function api_tag_end($html){
	echo "</ul></li>";
}
function api_end($url=''){
	echo "</ul>";
}

?>