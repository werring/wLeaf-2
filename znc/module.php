<?php
/*
 * class Znc_Module
 */

class Znc_Module  {
    /**
     * setting stores a znc setting
     * @staticvar array
     * @access public
    */
    public static $setting;
    
    /**
     * handles text from znc odules
    */
    public function handle(){
        switch(Irc_User::ident()){
            case "blockuser":
                switch(self::$setting["command"]){
                    case "list":
                        if(substr(Irc_Socket::$eLine[3],1,1) == "+"){
                            self::$setting["+counter"]++;
                            if(self::$setting["+counter"] >= 3){
                            Irc_Socket::write("NOTICE " . self::$setting["sender"] . " :End of banlist");
                                self::$setting = array();
                            }
                        } else {
                            foreach(Irc_Socket::$eLine as $key => $value){
                                if($key <= 2){
                                    continue;
                                }
                                switch(Irc_Format::removeColon($value)){
                                    case "":
                                    case "|":
                                    break;
                                    case (strlen($value)!= 0 && $value != "|"):
                                        self::$setting["params"][] = $value;
                                    break;
                                }
                            }
                            if(self::$setting["+counter"]==2)
                                Irc_Socket::write("NOTICE " . self::$setting["sender"] . " : - " . self::$setting["params"][0]);
                            
                            self::$setting["params"] = array();
                        }

                    break;
                    case "block":
                        
                    break;
                }
            break;
            case "status":
                
            break;
            
        }
    
    }
}
?>