<?php
function error($tag,$error,$trace=true){
    if(DEBUG_MODE){
        if($trace){
        	$backtrace = debug_backtrace();
        	$caller = end($backtrace);
        	echo "<b style='color:red'>FILE:</b>".$caller['file']."</br>";
        	echo "<b style='color:red'>LINE:</b>".$caller['line']."</br>";
        }
        echo  "<b style='color:red'>".$tag.":</b>".$error."</br>";
    }
}
?>