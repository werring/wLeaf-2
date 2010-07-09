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
    public function add($host,$auth,$ident,$nick = null){
        $requestedData[] = "auth";
        
        $fields[]          = "auth";
        $fields[]          = "hostmask";
        $fields[]          = "ident";
        $where['hostmask'] = trim($host);
        $where['ident']    = trim($ident);
        $result = Database_Mysql::select('IrcUserData',$fields,$where);
        if($result['affectedRows'] != 0){
            $oldAuth  = $result[0]['auth'];
            $oldHost  = $result[0]['host'];
            $oldIdent = $result[0]['ident'];
            switch($host){
                case "user.znc.treefamily.nl":
                    $data['ident'] = $ident;
                    $data["host"] = $host;
                    $data["auth"] = '$user$';
                    $data["time"] = time();
                    Database_Mysql::insert("auth",$data);
                    return true;
                break;
                case "staff.znc.treefamily.nl":   
                    $data['ident'] = $ident;
                    $data["host"] = $host;
                    $data["auth"] = '$staff$';
                    $data["time"] = time();
                    Database_Mysql::insert("auth",$data);
                    return true;            
                break;
                case (strlen($oldHost) > 0):
                    if($auth == $oldAuth && $ident == $oldIdent){
                        return true;
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
