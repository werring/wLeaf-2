<?php
/**
 * Quit
 *
 * @param (optional) message
*/

if(isset(Irc_Command::$params[0])){
    $message = Irc_User::fullHost() . ": " . join(" ",Irc_Command::$params); 
} else {
    $message = Irc_User::fullHost() . ": Quit"; 
}

Irc_Socket::sendText($message,WleafConfig::getConf("teamChan"));
sleep(2);
Irc_Socket::close($message);

?>