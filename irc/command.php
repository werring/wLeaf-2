<?php
/*
 * class Irc_Commands
 */

class Irc_Command  {
    
    /**
     * stores all commandparameters
     *
     * @access public
     * @staticvar array parameters of a command
    */
    public static $params = null;
    /**
     * Will we get multiple lines of data for this command?
     *
     * @access public
     * @staticvar boolean default: false
    */
    public static $multiLine = false;
    /**
     * Stores multiline data
     *
     * @access public
     * @staticvar all $multiLineData Stores multiline data
    */
    public static $multiLineData;
    
    
    protected function getCommandCode($command){
    $query =    "SELECT commands.code
                FROM `commands`
                JOIN accounts ON commands.access >= accounts.privileges
                JOIN auth ON accounts.auth = auth.auth
                WHERE auth.hostmask='".Irc_User::host()."' AND 
                commands.command='".$command."'";
        $data = Database_Mysql::advancedSelect($query);
        if($data["affectedRows"]== 0 ){
            return false;
        }
        return $data[0]["code"];
    }

    protected function getCommandHelp($command){
    $query =    "SELECT commands.help,commands.usage
                FROM `commands`
                JOIN accounts ON commands.access >= accounts.privileges
                JOIN auth ON accounts.auth = auth.auth
                WHERE auth.hostmask='".Irc_User::host()."' AND 
                commands.command='".$command."'";
        $data = Database_Mysql::advancedSelect($query);
        if($data["affectedRows"]== 0 ){
            return false;
        }
        return $data[0];
    }

    public function handleCommand($command,$params=null){
        $data = Database_Mysql::select("commands",array("command"));
        foreach ($data as $key => $value){
            $data[$key] = $value['command'];
        }
        $closest = Irc_Format::closest_word($command,$data,$percent);
        $percent = round($percent * 100, 2);
        if ($percent == 100) {
            self::$params = $params;
            $executed = self::execCommand($closest);
            
        } elseif($percent >= 75) {
            if(Znc_User::getAccessFromHost(Irc_User::host()) > 200){
                Irc_Socket::write("NOTICE " . Irc_User::nick() . " :Command not found, but perhaps you mean " . $closest . "?");                
            }
        }
    }
    
    public function handleMultiLineCommand(){
        if(preg_match("/^\+\-/",Irc_Socket::$eLine[0])){
            if(!isset(self::$multiLineData["pluscounter"])){
                self::$multiLineData["pluscounter"] = 1;
            } else {
                self::$multiLineDatap["pluscounter"]++;
            }
        }
    }
    
    public function naam(){
        
    }
    
    public function handleError($message="unknown error occured",$sendErrorReply=true){
        Irc_Format::log($message,"ERROR");
        if($sendErrorReply)
            Irc_Socket::write("NOTICE " . Irc_User::nick() . " :Error: " . $message);
    }
    
}
?>