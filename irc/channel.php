<?php
/*
 * class Irc_Channel
 */
class Irc_Channel  {
    /**
     * get channelname
     * @return mixed channelname or boolean false
    */
    public function getChan(){
        $chan = Irc_Socket::$eLine[3];
        if(preg_match("/^\#/",trim($chan))){
            return $chan;
        } else {
            foreach(Irc_Socket::$eLine as $line){
                if(preg_match("/^\#/",trim($line))){
                    return $line;
                } elseif(preg_match("/^:\#/",trim($line))) {
                    return substr($line,1);
                }
            }
            return false;
        }
    }
    /**
     * check if input is a channel
     * @access public
     * @param (String) $chan string to be checked if it is a channel
     * @return (Boolean) returns true false on given query
    */
    public function isChan($chan){
        if(preg_match("/^\#[^,\s]*/",trim($chan))){
            return true;
        } else {
            return false;
        }
    
    }
}
?>