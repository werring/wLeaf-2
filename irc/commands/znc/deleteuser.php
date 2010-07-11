<?php
/**
 * deletes user from ZNC and removes all access
 *
 * @param *auth|account
*/
    if(isset(Irc_Command::$params[0])){
        if("*" != substr(Irc_Command::$params[0],0,1)){
            $account = Irc_Command::$params[0];
            $auth    = Znc_User::getAuthFromAccount($account);
        } else {
            $auth    = substr(Irc_Command::$params[0],1);
            $account = Znc_User::getAccountFromAuth($auth);
        }
        if($account && $auth){
            if(Znc_User::getAccessFromAccount($account)<Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::ident())){
                Irc_Socket::write("ZNC *admin deluser " . $account);
                Irc_Socket::write("CS #tree fire *" . $auth . " 1");
                $table  = 'access';
                $where['account'] = $account;
                $where['auth']    = $auth;
                Database_Mysql::remove($table,$where);
                Irc_Socket::noticeNick("Account " . $account . " has been deleted");
            } else {
                Irc_Socket::noticeNick("You can only delete accounts with less access then yourself");
            }
        } else {
            Irc_Socket::noticeNick("Can't find the account you want to delete");
        }
    } else {
        Irc_Socket::noticeNick("Missing parameter");
    }
    

?>