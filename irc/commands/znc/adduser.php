<?php
/**
 * adds a user to ZNC granting access at all necessary places
 * @param nick
 * @param auth (optional)
 * @param account (optional)
*/
$pCount = count(Irc_Command::$params);

switch($pCount){
    case '0':
        Irc_Socket::noticeNick("Error, not enough params");
        $error = true;
    break;
    case '1':
        $nick    = Irc_Command::$params[0];
        $auth    = Irc_Command::$params[0];
        $account = Irc_Command::$params[0];
    break;
    case '2':
        $nick    = Irc_Command::$params[0];
        $auth    = Irc_Command::$params[1];
        $account = Irc_Command::$params[1];
    break;
    default:
        $nick    = Irc_Command::$params[0];
        $auth    = Irc_Command::$params[1];
        $account = Irc_Command::$params[2];
    break;
}
if(!preg_match('/^[a-zA-Z][a-zA-Z0-9_.@-]*$/',$account)){
    $error = true;
    Irc_Socket::noticeNick("Error, accountname is invalid");
}

if(!$error){
    $password = substr(base64_encode(uniqid()),0,8);
    Irc_Socket::write("ZNC *admin cloneuser clone " . $account);
    
    Irc_Socket::write("ZNC *admin set password " . $account . " " . $password);
    Irc_Socket::write("ZNC *admin set ident " . $account . " " . $account);
    Irc_Socket::write("ZNC *admin set RealName " . $account . " " . $auth . " ~ TreeZNC");
    Irc_Socket::write("ZNC *admin set nick " . $account . " " . $nick);
    Irc_Socket::write("ZNC *admin set altnick " . $account . " " . $nick . "`");
     
    Irc_Socket::write("CS #tree add *" . $auth . " 1");
    Irc_Socket::write("CS #tree voice " . $nick);
    
    Irc_Socket::sendText(Irc_Format::BOLD() . "Server:" . Irc_Format::BOLD() . " " . Irc_Format::UNDERLINED() ."user.znc.treefamily.nl" . Irc_Format::UNDERLINED() . " " . Irc_Format::BOLD() . "port:" . Irc_Format::BOLD() . "  6667 / +7776",$nick,1);
    Irc_Socket::sendText(Irc_Format::BOLD() . "Username:" . Irc_Format::BOLD() . " " . $account . " " . Irc_Format::BOLD() . "password:" . Irc_Format::BOLD() . " " . $password,$nick,1);
    Irc_Socket::sendText(Irc_Format::BOLD() . "Webinterface:" . Irc_Format::BOLD() . " " . Irc_Format::UNDERLINED() ."http://user.znc.treefamily.nl" . Irc_Format::UNDERLINED(),$nick,1);
    Irc_Socket::sendText(Irc_Format::BOLD() . "Commands:" . Irc_Format::BOLD() . " /as addmask *@*.znc.treefamily.nl",$nick,1);
    Irc_Socket::sendText(Irc_Format::BOLD() . "mIRC Commands:" . Irc_Format::BOLD() . " /server -a user.znc.treefamily.nl -p 6667 -g TreeZNC -w " . $account . ":" . $password . " -d TreeZNC",$nick,1);
    Irc_Socket::sendText(Irc_Format::BOLD() . "mIRC Commands:" . Irc_Format::BOLD() . " /server TreeZNC",$nick,1);
    
    $table = 'access';
    $data = array();
    $data['account'] = $account;
    $data['auth']    = $auth;
    $data['access']  = 100;
    Database_Mysql::insert($table,$data);
    Irc_Socket::noticeNick("Account " . $account . " created");
    if(Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::ident())>=400){
        Irc_Socket::noticeNick(Irc_Format::BOLD() . "Account:" . Irc_Format::BOLD() . " " .  $account . " " . Irc_Format::BOLD() . "Auth:" . Irc_Format::BOLD() . " " .  $auth . " " . Irc_Format::BOLD() . "Password:" . Irc_Format::BOLD() . " " .  $password);
    }
}
?>