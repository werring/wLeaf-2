<?php
    /**
     * Grands voice to user
     * @params nick and/or channel
    */
    switch(count(Irc_Command::$params) . ""){
        case '0':
            $chan = Irc_Channel::getChan();
            $nick = Irc_User::nick();
        break;
        case '1':
            if(Irc_Channel::isChan(Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $nick = Irc_User::nick();
            } else {
                $chan = Irc_Channel::getChan();
                $nick = Irc_Command::$params[0];
            }
        break;
        default:
            if(Irc_Channel::isChan(Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $nick = Irc_User::$params[1];
            } else {
                $chan = Irc_Command::$params[1];
                $nick = Irc_Command::$params[0];
            }
        break;
    }
    Irc_Socket::write("MODE " . $chan . " +v " . $nick);
    Irc_Socket::noticeNick("Voiced " . $nick . " in " . $chan);
?>