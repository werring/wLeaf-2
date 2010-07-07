<?php

function looptroug($array){
    foreach($array as $key => $value){
        if(is_array($value)){
            $get[$key] = looptroug($value);
        } else {
            $get[$key] = $value;
        }
    }
    return $get;
}

print_r(looptroug($_GET));


?>