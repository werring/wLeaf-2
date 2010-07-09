<?php

class Znc_User  {
    /**
     * getAccountFromHost
     *
     * Gets the accountname of a user by its host
     *
     * @access public
     * @param string $host Hostname
     * @return String/Boolean Boolean if false, else an String with the accountname
    */
    public function getAccountFromHost($host,$ident){
        $query = "SELECT access.account" . PHP_EOL;
        $query.= "FROM IrcUserData" . PHP_EOL;
        $query.= "INNER JOIN access ON IrcUserData.auth = access.auth" . PHP_EOL;
        $query.= "WHERE `host` LIKE '" . $host . "' AND `ident` LIKE '".$ident."'" . PHP_EOL;
        $data = Database_Mysql::advancedSelect($query);
        if($data)
            return $data[0]["account"];
        else
            return false;
    }
    /**
     * getAccountFromAuth
     *
     * Gets the accountname of a user by its auth
     *
     * @access public
     * @param string $auth auth
     * @return String/Boolean Boolean if false, else an String with the accountname
    */  
    public function getAccountFromAuth($auth){
        $table = "access";
        $fields[] = "account";
        $where["auth"] = $auth;
        $data = Database_Mysql::select($table,$fields,$where);
        if($data)
            return $data[0]["account"];
        else
            return false;
    }
    /**
     * getAccessFromHost
     *
     * Gets the accesslevel of a user by its host
     *
     * @access public
     * @param string $host Hostname
     * @return String/Boolean Boolean if false, else an String with the accesslevel (numeric)
    */
    public function getAccessFromHost($host,$ident){
        $query = "SELECT access.access" . PHP_EOL;
        $query.= "FROM IrcUserData" . PHP_EOL;
        $query.= "INNER JOIN access ON IrcUserData.auth = access.auth" . PHP_EOL;
        $query.= "WHERE `host` LIKE '" . $host . "' AND `ident` LIKE '".$ident."'" . PHP_EOL;
        $data = Database_Mysql::advancedSelect($query);
        var_dump($data);
        if($data)
            return $data[0]["access"];
        else
            return false;
    }
    /**
    * getAccessFromAuth
    *
    * Gets the accesslevel of a user by its auth
    *
    * @access public
    * @param string $auth auth
    * @return String/Boolean Boolean if false, else an String with the accesslevel (numeric)
   */
    public function getAccessFromAuth($auth){
        $table = "access";
        $fields[] = "access";
        $where["auth"] = $auth;
        $data = Database_Mysql::select($table,$fields,$where);
        if($data)
            return $data[0]["access"];
        else
            return false;
    }
    /**
    * getAccesFromAccount
    *
    * Gets the accountname of a user by its auth
    *
    * @access public
    * @param string $account accountname
    * @return String/Boolean Boolean if false, else an String with the accesslevel
   */
    public function getAccessFromAccount($account){
        $table = "access";
        $fields[] = "access";
        $where["account"] = $account;
        $data = Database_Mysql::select($table,$fields,$where);
        if($data)
            return $data[0]["access"];
        else
            return false;
    }
}

?>
