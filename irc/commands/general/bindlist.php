<?php
    $select[] = "*";
    $order['bind'] = 'ASC';
    $order['command'] = 'ASC';
    $binds = Database_Mysql::select('commands',$select,null,0,0,$order);
    var_dump($binds);
    foreach($binds as $key => $bind){
        if(is_numeric($key)){
            foreach($bind as $key2 => $value){
                if(strlen($value) > $lengths[$key2]){
                    $lengths[$key2] = strlen($value);
                }
            }
        }
    }
    foreach($binds as $key => $bind){
        $string = $bind['bind'] . "%s" . $bind['command'] . "%s" . $bind['access'];
        if(strlen($string)>4){
            Irc_Socket::noticeNick(sprintf($string,str_repeat(" ",($lengths['bind']-strlen($bind['bind'])+3)),str_repeat(" ",($lengths['command']-strlen($bind['command'])+3))));
        }
    }
?>