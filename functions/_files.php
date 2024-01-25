<?php


function upload_file($data=array()){
    if(is_array($data)){
        extract($data);
    }
    if(!isset($files)){
            error("FILE_ERROR",'file can not be empty');
    }   
    if(!is_array($files[key($files)])){   
            if(!isset($name)) {   
                $name=generate_random_string(5).time();
            }else{
				$name=$name.'-'.generate_random_string(5);
			}
            if(empty($files)){
                error("FILE_ERROR",'file can not be empty'); 
                message_redirect(array('message'=>'','type'=>'error'));
            }
            if(!isset($destination)){
                error("FILE_ERROR",' Destination must be defined');    
            }
            if(!is_dir($destination)){
                error("FILE_ERROR","Destination path($destination) does not exist");    
            }
            if($files['error']){
                error("FILE_ERROR","Error in file error:".$files['error']);    
            }
            $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
      
            if(isset($type)){
                $extention=explode(',',$type);
                if(!in_array($ext,$extention)){
                    message_redirect(array('message'=>"",'type'=>'error'));
                    error("FILE_ERROR","Invalid file type, valid type($type)");    
                }
            }
        
            if(isset($size)){
                if($size<1){
                    $sizetag='kb';
                    $up_size=$size*1000;
                }else{
                    $sizetag='mb';
                    $up_size=$size;
                }   
                if($files['size']>$size*1024*1024){
                    error("FILE_ERROR","Too large file size, valid size($up_size $sizetag)");  
                }
            }   
            
            if(move_uploaded_file($files['tmp_name'],$destination.'/'.$name.'.'.$ext)){
                return $name.'.'.$ext;
            }else{
                return false;
            }
    }else
    {
        if(!isset($name)){   
            $i=0;
            foreach($files['name'] as $val){
                if(!empty($val)){
                    $ext[$i] = strtolower(pathinfo($val, PATHINFO_EXTENSION));
                }   
                $names[$i]=generate_random_string(5).time();
                $i++;
            }
        }else{
            $i=0;
            foreach($files['name'] as $val){
                if(!empty($val)){
                    $ext[$i] = strtolower(pathinfo($val, PATHINFO_EXTENSION));
                }
                $names[$i]=$name.''.($i+1);
                $i++;
            }
        }
        
        if(!array_filter($files['name'])){
            error("FILE_ERROR","files can not be empty please upload atleast on file");  
        }
        
        if(!isset($destination)){
            error("FILE_ERROR","Destination must be defined");  
        }
        
        if(!is_dir($destination)){
            error("FILE_ERROR","Destination path($destination) does not exist");  
        }
            
        if(isset($type))    {
            $extention=explode(',',$type);
            foreach($ext as $key=>$val){
                if(!in_array($val,$extention)){
                    error("FILE_ERROR","Invalid file type , valid type($type) , FILE : ".$files['name'][$key]);      
                }
            }
            if(isset($size)){
                if($size<1){
                    $sizetag='kb';
                    $up_size=$size*1000;
                }else{
                    $sizetag='mb';
                    $up_size=$size;
                }   
                
                foreach($files['size'] as $key=>$val){
                    if($val>$size*1024*1024){
                        error("FILE_ERROR","Too large file size, valid size($up_size $sizetag) , FILE : ".$files['name'][$key]);  
                    }
                }
            }
            
            $return_file=array();
            foreach($files['tmp_name'] as $key=>$val){
                if(!empty($val)){   
                    if(move_uploaded_file($val,$destination.'/'.$names[$key].'.'.$ext[$key])){
                        $return_file[]= $names[$key].'.'.$ext[$key];
                    }
                }
            }
            return implode(',',$return_file);
        }   
    }
}

// *********upload multiple files*************
function multiple_upload_file($data_all=array())
{
    if(is_array($data_all))
    {
        extract($data_all);
    }
    if(!isset($files))
    {
        message_redirect(array('message'=>'function upload_file Error: file can not be empty','type'=>'error'));
        die;
    }   
    if(!is_array($files[key($files)]))
    {   
                        
        if(!isset($name))
        {   
            $name=generate_random_string(5).time();
        }
        if(empty($files))
        {
            message_redirect(array('message'=>'function upload_file Error: file can not be empty','type'=>'error'));
            die;
        }
        if(!isset($destination))
        {
            message_redirect(array('message'=>'function upload_file Error: Destination must be defined','type'=>'error'));
            die;
        }
        if(!is_dir($destination))
        {
            message_redirect(array('message'=>"function upload_file Error: Destination path($destination) does not exist",'type'=>'error'));
            die;
        }
        if($files['error'])
        {
            message_redirect(array('message'=>"function upload_file Error: Error in file error:".$file['error'],'type'=>'error'));
            die;
        }
        $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
        if(isset($type))
        {
            $extention=explode(',',$type);
            if(!in_array($ext,$extention))
            {
                message_redirect(array('message'=>"Invalid file type, valid type($type)",'type'=>'error'));
                die;
            }
            
        }
        if(isset($size))
        {
            if($size<1)
            {
                $sizetag='kb';
                $up_size=$size*1000;
            }
            else
            {
                $sizetag='mb';
                $up_size=$size;
            }   
            if($files['size']>$size*1024*1024)
            {
                message_redirect(array('message'=>"Too large file size, valid size($up_size $sizetag)",'type'=>'error'));
                die;
            }
        }   
        if(move_uploaded_file($files['tmp_name'],$destination.'/'.$name.'.'.$ext))
        {
            return $name.'.'.$ext;
        }
        else
        {
            return false;
        }
    }
    else
    {
        if(!isset($name))
        {   
            $i=0;
            foreach($files['name'] as $val)
            {
                if(!empty($val))
                {
                    $ext[$i] = strtolower(pathinfo($val, PATHINFO_EXTENSION));
                }   
                $names[$i]=generate_random_string(5).time();
                $i++;
            }
        }
        else
        {
            $i=0;
            foreach($files['name'] as $val)
            {
                if(!empty($val))
                {
                    $ext[$i] = strtolower(pathinfo($val, PATHINFO_EXTENSION));
                }
                $names[$i]=$name.''.($i+1);
                $i++;
            }
        }
        if(!array_filter($files['name']))
        {
            message_redirect(array('message'=>'function upload_file Error: files can not be empty please upload atleast on file','type'=>'error'));
            die;
        }
        if(!isset($destination))
        {
            message_redirect(array('message'=>'function upload_file Error: Destination must be defined','type'=>'error'));
            die;
        }
        if(!is_dir($destination))
        {
            message_redirect(array('message'=>"function upload_file Error: Destination path($destination) does not exist",'type'=>'error'));
            die;
        }
        if(isset($type))
        {
            $extention=explode(',',$type);
            
            foreach($ext as $key=>$val)
            {
                if(!in_array($val,$extention))
                {
                    message_redirect(array('message'=>"Invalid file type , valid type($type) , FILE : ".$files['name'][$key],'type'=>'error'));
                    die;
                }
            }
        }
        if(isset($size))
        {
            if($size<1)
            {
                $sizetag='kb';
                $up_size=$size*1000;
            }
            else
            {
                $sizetag='mb';
                $up_size=$size;
            }   
            foreach($files['size'] as $key=>$val)
            {
                if($val>$size*1024*1024)
                {
                    message_redirect(array('message'=>"Too large file size, valid size($up_size $sizetag) , FILE : ".$files['name'][$key],'type'=>'error'));
                    die;
                }
            }
        }
        $return_file=array();
        foreach($files['tmp_name'] as $key=>$val)
        {
            if(!empty($val))
            {   
                
                if(move_uploaded_file($val,$destination.'/'.$names[$key].'.'.$ext[$key]))
                {
                    $return_file[]= $names[$key].'.'.$ext[$key];
                }
                
            }
        }
        return implode(',',$return_file);
    }   
}

?>