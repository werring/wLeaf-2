<?php
/**
 * bind a command to a commandfile
 * @param command
 * @param file (directory.file)
 * @param access
*/
        $maxAccess = Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::Ident());
        $bind = Irc_Command::$params[1];
        $file = "irc/commands/" . str_replace('.','/',$bind) . ".php";
        if(file_exists($file)){
            if($maxAccess >= Irc_Command::$params[2] || ($maxAccess >= 500 && Irc_Command::$params[2] == ($maxAccess+1))){
                $data = Database_Mysql::select("commands",array("command","bind","access"),array("bind"=>$bind));
                foreach ($data as $key => $value){
                    $commands[$key]     = $value['command'];
                    $binds[$key]        = $value['bind'];
                    $accessNeeded[$key] = $value['access'];
                }
                if(!in_array(Irc_Command::$params[0],$commands)){
                    foreach($binds as $key => $bindcmd){
                        if($accessNeeded[$key] <= Irc_Command::$params[2] || $accessNeeded[$key] <= $maxAccess){
                            $ok = true;
                            break;
                        } else {
                            $ok = false;
                        }
                    }
                    if($ok){
                        $data = array();
                        $data['bind'] = $bind;
                        $data['command'] = strtolower(Irc_Command::$params[0]);
                        $data['access'] = Irc_Command::$params[2];
                        Database_Mysql::insert("commands",$data);
                        Irc_Socket::noticeNick("Command '".$data['command']."' binded to " . $data['bind'] . " Access: " . $data['access']);
                    } else {
                        Irc_Socket::noticeNick("Your access is to low to bind " . $bind);
                    }
                    
                } else {
                    Irc_Socket::noticeNick("This command already has a bind");
                    var_dump(Irc_Command::$params[0],$commands);
                }
            } else {
                Irc_Socket::noticeNick("You can't bind a command above your own access (" . $maxAccess . ")");
            }
        } else {
            Irc_Socket::noticeNick("The function " . $bind . " has not been found");
        }
?>
