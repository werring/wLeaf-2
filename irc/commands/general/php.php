<?php
if(count(Irc_Command::$params)>0){
    $output = eval("return " . implode(" ",Irc_Command::$params) . ";");
    $output = explode("\n",$output);
    foreach($output as $lineNumber => $line){
        switch($lineNumber){
            case ($lineNumber > 15):
                sleep(1);
            case ($lineNumber > 10):
                sleep(1);
            case ($lineNumber > 5):
                sleep(1);
        }
        Irc_Socket::noticeNick($line);
    }
} else {
    Irc_Socket::noticeNick("No text to send");
}
?>