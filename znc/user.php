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
    public function getAccountFromHost($host){
        $query = "SELECT accounts.account" . PHP_EOL;
        $query.= "FROM auth" . PHP_EOL;
        $query.= "INNER JOIN accounts ON auth.auth = accounts.auth" . PHP_EOL;
        $query.= "WHERE `hostmask` LIKE '" . $host . "'" . PHP_EOL;
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
        $table = "accounts";
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
    public function getAccessFromHost($host){
        $query = "SELECT accounts.privileges" . PHP_EOL;
        $query.= "FROM auth" . PHP_EOL;
        $query.= "INNER JOIN accounts ON auth.auth = accounts.auth" . PHP_EOL;
        $query.= "WHERE `hostmask` LIKE '" . $host . "'" . PHP_EOL;
        $data = Database_Mysql::advancedSelect($query);
        if($data)
            return $data[0]["privileges"];
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
        $table = "accounts";
        $fields[] = "privileges";
        $where["auth"] = $auth;
        $data = Database_Mysql::select($table,$fields,$where);
        if($data)
            return $data[0]["privileges"];
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
        $table = "accounts";
        $fields[] = "privileges";
        $where["account"] = $account;
        $data = Database_Mysql::select($table,$fields,$where);
        if($data)
            return $data[0]["privileges"];
        else
            return false;
    }
}

?>
