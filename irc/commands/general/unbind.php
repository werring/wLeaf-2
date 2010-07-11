<?php
/**
 * Removes the bind between a command and the commandfile
 * @param command
*/
    $command  = Irc_Command::$params[0];
    $myAccess = Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::Ident());

    $data = array();
    $data[] = 'bind';
    $data[] = 'access';
    $where['command'] = $command;
    $info = Database_Mysql::select('commands',$data,$where);
    if($info['affectedRows'] > 0){
        $access = $info[0]['access'];
        $bind   = $info[0]['bind'];
        if($access <= $myAccess || ($myAccess>=500 && $access == ($myAccess+1))){
            Database_Mysql::remove('commands',$where);
            Irc_Socket::noticeNick("Command " . $command . " has been unbinded from " . $bind . " (" . $access . ")");            
        } else {
            Irc_Socket::noticeNick("Your access is to low to unbind this command");
        }
    } else {
        Irc_Socket::noticeNick("Command " . $command . " was not found");
    }
?>