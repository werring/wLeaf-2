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
        $fields[]          = "auth";
        $fields[]          = "host";
        $fields[]          = "ident";
        $where['host'] = trim($host);
        $where['ident']    = trim($ident);
        $result = Database_Mysql::select('IrcUserData',$fields,$where);
        if($result['affectedRows'] != -1){
            if(isset($result[0])){
                $oldAuth  = $result[0]['auth'];
                $oldHost  = $result[0]['host'];
                $oldIdent = $result[0]['ident'];
            }
            switch($host){
                case "user.znc.treefamily.nl":
                    $data['ident'] = $ident;
                    $data["host"] = $host;
                    $data["auth"] = '$user$';
                    Database_Mysql::insert("IrcUserData",$data);
                    return true;
                break;
                case "staff.znc.treefamily.nl":   
                    $data['ident'] = $ident;
                    $data["host"] = $host;
                    $data["auth"] = '$staff$';
                    Database_Mysql::insert("IrcUserData",$data);
                    return true;            
                break;
                case (strlen($oldHost) > 0):
                    if($auth == $oldAuth && $ident == $oldIdent){
                        return true;
                    } else {
                        $data['ident'] = $ident;
                        $data["host"] = $host;
                        $data["auth"] = $auth;
                        Database_Mysql::insert("IrcUserData",$data);
                    return true;
                    }
                break;
                default:
                    $data['ident'] = $ident;
                    $data["host"] = $host;
                    $data["auth"] = $auth;
                    Database_Mysql::insert("IrcUserData",$data);
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
     * @param string $ident ident
     * @return string authname
    */
    public function get($host,$ident){
        $info["host"] = $host;
        $info['ident']= $ident;
        $fields[] = "auth";
        $data = Database_Mysql::select("IrcUserData",$fields,$info,1);
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
     * @param string $ident ident
     * @return boolean succes
    */
    public function delete($host,$ident){
        $info["host"] = $host;
        $info["ident"] = $ident;
        $data = Database_Mysql::remove("IrcUserData",$info);
        if(1==$data["affectedRows"]){
            return true;
        } else {
            return false;
        }
    }
}

?>
