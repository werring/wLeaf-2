<?php
/*
 * class Irc_Commands_Error
 */

class Irc_Commands_Error  {
    public function handle($error=true,$level=0,$info=null){
        if($error === true){
            Irc_Socket::write("NOTICE " . Irc_User::nick() . " :error occured");
        } else {
            
        }
    
    }
}
?>