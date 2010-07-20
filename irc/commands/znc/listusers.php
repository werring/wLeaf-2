<?php
    /**
     * Lists all users from the access database
     *
     * @param (optional) match
    */
    
    $table = "access";
    $select[] = "*";
    $where['account'] = "%".Irc_Command::$params[0]."%";
    $order['id'] = "ASC";
    $data = array();
    $data = Database_Mysql::select($table, $select, $where,0,0,$order);
    $lengths = array();
    $messages = array();
    $lengths['id'] = strlen("ID");
    $lengths['account'] = strlen("Account");
    $lengths['access'] = strlen("Access");
    $lengths['auth'] = strlen("Auth");
    foreach($data as $key => $info){
        if(!is_numeric($key)) continue;
        foreach($info as $field => $value){
            if($field == "banned"){
                if($value!="0000000000") {
                    $value = date("d-m-Y",$value);
                }
                else {
                    $value = "-";
                }
                $data[$key]["banned"] = $value;
            }
            if(strlen($value)>$lengths[$field]){
                $lengths[$field] = strlen($value);
            }
        }
    }
    
    
    Irc_Socket::noticeNick(sprintf("ID%sAccount%sAuth%sAccess%sBanned",str_repeat(" ",$lengths['id']-2+3),str_repeat(" ",$lengths['account']-7+3),str_repeat(" ",$lengths['auth']-4+3),str_repeat(" ",$lengths['access']-6+3)));
    foreach($data as $key => $info){
        if(!is_numeric($key)) continue;
        foreach($info as $field => $value){        
            $length[$field] = strlen($value);
        }
        if(count($data) > 10) sleep(1);
        Irc_Socket::noticeNick(sprintf("$info[id]%s$info[account]%s$info[auth]%s$info[access]%s$info[banned]",str_repeat(" ",$lengths['id']-$length['id']+3),str_repeat(" ",$lengths['account']-$length['account']+3),str_repeat(" ",$lengths['auth']-$length['auth']+3),str_repeat(" ",$lengths['access']-$length['access']+3)));
    }
?>