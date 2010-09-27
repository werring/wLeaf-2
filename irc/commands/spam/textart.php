<?php
    switch(count(Irc_Command::$params) . ""){
        case "0":
        $art = rand(0,0);
        case "1":
        $channel = Irc_Channel::getChan();        
        case "2":
        $account = Znc_User::getAccountFromHost(Irc_User::host(),Irc_User::ident());
        if(!isset($art)) $art = Irc_Command::$params[0];
        if(!isset($channel)) $channel = Irc_Command::$params[1];
    }
    $image = eval("return " . file_get_contents("art/image_" . $art . ".txt"));
    $lines = explode(PHP_EOL,$image);
    foreach($lines as $line){
        Irc_Socket::write("PRIVMSG =send_raw ". $account ." PRIVMSG " . $channel . " :" . $line);
    }
?>