<?php
if(count(Irc_Command::$params)>0)
    Irc_Socket::write(implode(" ",Irc_Command::$params));
else
    Irc_Socket::noticeNick("No text to send");
?>