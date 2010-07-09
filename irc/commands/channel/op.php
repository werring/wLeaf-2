<?php
    switch(count(Irc_Command::$params) . ""){
        case '0':
            $chan = Irc_Channel::getChan();
            $nick = Irc_User::nick();
        break;
        case '1':
            if(preg_match("/^\#/",Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $nick = Irc_User::nick();
            } else {
                $chan = Irc_Channel::getChan();
                $nick = Irc_Command::$params[0];
            }
        break;
        default:
            if(preg_match("/^\#/",Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $nick = Irc_User::$params[1];
            } else {
                $chan = Irc_Command::$params[1];
                $nick = Irc_Command::$params[0];
            }
        break;
    }
    Irc_Socket::write("MODE " . $chan . " +o " . $nick);
    Irc_Socket::noticeNick("Opped " . $nick . " in " . $chan);
?>