<?php
/*
 * class Irc_Channel
 */
class Irc_Channel  {
    public function getChan(){
        $chan = Irc_Socket::$eLine[3];
        if(preg_match("/^\#/",trim($chan))){
            return $chan;
        } else {
            foreach(Irc_Socket::$eLine as $line){
                if(preg_match("/^\#/",trim($line))){
                    return $line;
                }
            }
        }
    }
}
?>