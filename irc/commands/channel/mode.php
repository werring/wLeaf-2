<?php
    /**
     * sets channel mode to given parameter
     * @params channelmodes, parameters and/or channel
    */
    switch(count(Irc_Command::$params) . ""){
        case '0':
            $error = true;
            break;
            $chan = Irc_Channel::getChan();
            $nick = Irc_User::nick();
        break;
        case '1':
            if(Irc_Channel::isChan(Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $error = true;
            } else {
                $chan = Irc_Channel::getChan();
                $modes = Irc_Command::$params[0];
            }
        break;
        case '2':
            if(Irc_Channel::isChan(Irc_Command::$params[0])){
                $chan = Irc_Command::$params[0];
                $modes = Irc_Command::$params[1];
            } else {
                $chan = Irc_Channel::getChan();
                $modes = join(" ",Irc_Command::$params);
            }
        break;
        default:
            if(Irc_Channel::isChan(Irc_Command::$params[0])){
                $chan = array_shift(Irc_Command::$params);
                $modes = join(" ",Irc_Command::$params);
            } else {
                $chan = Irc_Channel::getChan();
                $modes = join(" ",Irc_Command::$params);
            }
            
        break;
    }
    if(!isset($error)){
        Irc_Socket::write("MODE " . $chan . $modes);
        Irc_Socket::noticeNick("Setted modes " . $modes . " in " . $chan);
    } else {
        Irc_Socket::noticeNick("Not enough params");
    }
?>