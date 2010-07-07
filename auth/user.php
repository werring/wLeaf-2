<?php

/*
 * class Auth_User
 */

class Auth_User  {
    /**
     * adds $auth & $host to the table "auth"
     *
     * @param string $host hostname
     * @param string $auth authname
     * @access public
     * @return boolean success
    */
    public function add($host,$auth,$nick = null){
        $requestedData[] = "auth";
        
        $qry = "SELECT auth,hostmask FROM auth WHERE `hostmask`='".trim($host) . "'";
        $result = Database_Mysql::sqlQry($qry);
        if($result){
            $old = mysql_fetch_array($result);
            $oldAuth = $old[0];
            $oldHost = $old[1];
            switch($host){
                case "user.znc.treefamily.nl":
                    $data["hostmask"] = $host;
                    $data["auth"] = "$user$";
                    $data["time"] = time();
                    Database_Mysql::insert("auth",$data);
                    return true;
                break;
                case "staff.znc.treefamily.nl":   
                    $data["hostmask"] = $host;
                    $data["auth"] = "$staff$";
                    $data["time"] = time();
                    Database_Mysql::insert("auth",$data);
                    return true;            
                break;
                case (strlen($oldHost) > 0):
                    if($auth == $oldAuth){
                        return true;
                    } else {
                        $table = "auth";
                        $data["auth"] = "$multipleAuths$";
                        $where["hostmask"] = $host;
                        Database_Mysql::update($table,$data,$where);
                        return false;
                    }
                break;
                default:
                    $data["hostmask"] = $host;
                    $data["auth"] = $auth;
                    $data["time"] = time();
                    Database_Mysql::insert("auth",$data);
                    return true;
                break;
            }
        } else {
            return false;
        }
    }
    
    /**
     * get authname from host
     *
     * @access public
     * @param string $host hostname
     * @return string authname
    */
    public function get($host){
        $info["hostmask"] = $host;
        $fields[] = "auth";
        $data = Database_Mysql::select("auth",$fields,$info,1);
        if(isset($data["auth"])){
            return $data["auth"];
        } else {
            return false;
        }
    }
    
    /**
     * removes host from database
     *
     * @access public
     * @param string $host hostname
     * @return boolean succes
    */
    public function delete($host){
        $info["hostmask"] = $host;
        $data = Database_Mysql::remove("auth",$info);
        if(1==$data["affectedRows"]){
            return true;
        } else {
            return false;
        }
    }
}

?>
