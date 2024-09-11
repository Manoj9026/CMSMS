<?php
date_default_timezone_set('Asia/Colombo');
function escape($string){
    return htmlentities($string,ENT_QUOTES,'UTF-8');
}
function clearInt($id){
    return preg_replace('#[^0-9]#i', '', $id);
}

function clearVar($id){
    return preg_replace('#[^a-z-_]#i', '', $id);
}

function clearSql($id){
    return preg_replace('#[^a-z-A-Z-0-9-.]#i', '',$id);
}

function limit($text,$limit){
     $text = strip_tags($text);
    return(strlen($text)>$limit)?substr($text, 0,$limit).'..':$text;    

}

function remove_space($s){
   $result = preg_replace('#[^a-z_]#i', '-', strtolower(str_replace('.', '', $s)));
    while(strpos($result,'--')!=FALSE){
         $result =    remove___D($result);
    }
    return $result;
}

function remove___D($str){
    return strtolower(str_replace("--", "-", $str));
    
}


function addtime($date){    
    $today = date("Y-m-d");            
    $eDate = date("Y-m-d",strtotime($date));            
   $result ="";
   if($eDate==$today){
    $r = strtotime(date("Y-m-d h:i:s"))-strtotime($date);
    // if(){}
    $result =  date("h:i",$r).' '.$r;
   }  else{
     $result = date("Y-m-d",  strtotime($date));  
}
    
    return $result;    
}



function getDT(){
    return date("Y-m-d h:i:s");
}


function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function json_validate($string) {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
    
    