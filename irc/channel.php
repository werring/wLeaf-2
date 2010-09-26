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
}
?>