<?php
/*
 * class Irc_Handle
 *
 * handles all IRC communication
 */

class Irc_Handle {


    /**
     * @staticvar boolean Are we in whois?
     * @access protected
     * 
    */
    protected static $whois = false;
    /**
     * @staticvar string hostmask of whoised user
     * @access protected
    */
    protected static $whoisHost;
    /**
     * @staticvar string authname of whoised user
     * @access protected
    */
    protected static $whoisAuth;
    
    /**
     * @staticvar string nick of whoised user
     * @access protected
    */
    protected static $whoisNick;
    
    /**
     * @staticvar string ident of whoised user
     * @access protected
    */
    protected static $whoisIdent;
    
    
    
    /**
     * initial handler for IRC replies
     *
     * @access public
    */
    public function handle(){
        switch(Irc_Socket::$eLine[0]){
            case "PING":
                unset(Irc_Socket::$eLine[0]);
                Irc_Socket::write("PONG " . implode(" ",Irc_Socket::$eLine));
            break;
            case "ERROR":
                unset(Irc_Socket::$eLine[0]);
                Irc_Socket::$eLine[1] = substr(Irc_Socket::$eLine[1],1);
                Irc_Socket::close(implode(" ",Irc_Socket::$eLine),"ERROR");
            break;
            default:
            Irc_Socket::$eLine[0] = Irc_Format::removeColon(Irc_Socket::$eLine[0]);
            Irc_Socket::$eLine[1] = Irc_Format::removeColon(Irc_Socket::$eLine[1]);
            if(!is_numeric(Irc_Socket::$eLine[1])){
                switch(Irc_Socket::$eLine[1]){
                    case "PRIVMSG":
			if(Irc_User::host()!= "znc.in" && substr(Irc_User::nick(),0,1) != "*"){
			    self::text();
			} else {
			    Znc_Module::handle();    
			}
                    break;
		case "JOIN":
		    
		break;
                }
            }
            else {
                self::Numeric();
            }
            break;
        }
    }
    
    /**
     * initial handler for nummeric replies
     *
     * @access protected
    */
    protected function Numeric(){
        switch(Irc_Socket::$eLine[1]){
            //WELCOME
            case (Irc_Socket::$eLine[1] <= 5):
            //LUSERS
            case ((Irc_Socket::$eLine[1] > 250)&&(Irc_Socket::$eLine[1] <= 255)):
            //MOTD
            case 372:
            case 375:
            case 376:
                self::Welcome();
            break;
	    case 324:
	    case 329:
	    case 332:
	    case 333:
	    case 353:
	    case 366:
		self::Channel();
	    break;
	    case 311:
	    case self::$whois:
		self::Whois();
	    break;
        }
    }
    
    /**
     * handles al channel replies
     * 
     * @return boolean succes
    */
    protected function Channel(){
        switch(Irc_Socket::$eLine[1]){
	    case 332:
		$line = Irc_Socket::$line;
		$channel = Irc_Socket::$eLine[3];
		$topic = explode(":",$line,3);
		$topic = $topic[2];
		Irc_Channel_Management::topic($channel,$topic);
		return true;
	    break;
	    case 353:
		$channel = Irc_Socket::$eLine[4];
		$users = Irc_Format::formatNames();
		foreach($users as $user){
		    $user = explode(",",$user);
		    $table = "nick";
		    $fields[] = "time";
		    $where["nick"] = $user[0];
		    $data = Database_Mysql::select($table,$fields,$where);
		    if(time() - $data[0]["time"] > WleafConfig::getConf("whoisOutdated")){
			Irc_Socket::write("Whois " . $user[0] . " " . $user[0]);
			$insert["nick"] = $user[0];
			$insert["time"] = time();
			Database_Mysql::insert($table,$insert);
		    }
		}
		return true;
	    break;
	    case 366:
		$channel = Irc_Socket::$eLine[3];
	        Irc_Socket::write("WHO " . $channel);
		return true;
	    break;
	    case (is_numeric(Irc_Socket::$eLine[1])):
		return true;
	    break;
        }
	
    }
    
    /**
     * handles all Welcome messages
     * 
     * @access protected
    */
    protected function Welcome(){
        switch(Irc_Socket::$eLine[1]){
        }
    }
    
    /**
     * handles all whois replies
     * 
     * @access proteced
    */
    protected function Whois(){
	switch(Irc_Socket::$eLine[1]){
	    case 311:
		self::$whois = true;
		self::$whoisHost = Irc_Socket::$eLine[5];
		self::$whoisIdent = Irc_Socket::$eLine[4];
	    break;
	    case 330:
		self::$whoisAuth = Irc_Socket::$eLine[4];
	    case 318:
		Auth_User::add(self::$whoisHost,self::$whoisAuth,self::$whoisIdent);
		self::$whois = false;
		self::$whoisAuth=null;
		self::$whoisHost=null;
		self::$whoisIdent=null;
	    break;
        }
    }
    
    protected function text(){
	$command = Irc_Format::removeColon(Irc_Socket::$eLine[3]);
	Irc_Command::$params = null;
	if(WleafConfig::getConf("cmdchar") == substr($command,0,1)){
	    foreach(Irc_Socket::$eLine as $key => $value){
		if($key <= 3){
		    continue;
		}
		$params[] = $value;
	    }
	    $command = substr($command,1);
	    Irc_Command::handleCommand($command,$params);
	}
	
    }
}
?>
