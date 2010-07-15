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
    public function cronjobDeleteAccounts(){
        $files = scandir("removeUserFromZNC");
        if(count($files)>2){
            array_shift($files);
            array_shift($files);
            $filepath = "removeUserFromZNC/";
            foreach($files as $file){
                    $fileText = file_get_contents($filepath. $file);
                    $data    = explode("|",$fileText);
                    $account = trim($data[0]);
                    $auth    = trim($data[1]);
                    $access  = trim($data[2]);
                    unlink($filepath.$file);
                    Irc_Socket::write("cs #tree fire *" . $auth);
                    Irc_Socket::write("ZNC *admin deluser " . $account);
                    Irc_Format::log("Cronjob deleted user " . $account . " (".$auth.") Access: " . $access,"CRON");
            }
        }    
    }
}
?>