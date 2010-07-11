<?php
if(count(Irc_Command::$params)>0)
    Irc_Socket::sendText(implode(" ",Irc_Command::$params),Irc_Channel::getChan());
else
    Irc_Socket::noticeNick("No text to send");
?>