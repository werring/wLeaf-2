<?php
/**
 * wLeaf
 *
 * IRC / ZNC administration bot
 * 
 * @author Werring <thomwerring@gmail.com>
 * @copyright Copyright © 2010, Thom Werring
 * @version 2.1.0
*/


define("CONNECTED", true);
define("DISCONNECTED", false);
define("DEBUG",false);


//error_reporting(0);

/**
 * Auto loads classfiles
*/
function __autoload($class_name) {
    $class = strtolower(implode("/",explode("_",$class_name)). ".php");
    require_once $class;
}

Irc_Format::log("Starting wLeaf v2.1.0");
Irc_Format::log("database","INIT");
$sql = new Database_Mysql();
$sql->clear("IrcUserData");
Irc_Format::log("configuration","INIT");
$config = new WleafConfig();
Irc_Format::log("irc connection","INIT");
$socket = new Irc_Socket();
Irc_Format::log("Internal Adress List", "INIT");
$ial = new Irc_Channel_IAL($config->getConf('network'));

/**
 * IRC startup 
 *
 * sends user, nick and password lines
*/
$socket->write("USER ".$config->getConf("ident")." ".$config->getConf("ident")." ".$config->getConf("server")." :".$config->getConf("realname"));
$socket->write("PASS ".$config->getConf("password"));
$socket->write("NICK ".$config->getConf("nick"));

while(Irc_Socket::$connected){
    $time = time();
    /**
     * DEBUG only run for ~ 120 seconds
    */
    if(($time-120)>$config->getConf("startupTime") && DEBUG){
        $socket->write("QUIT");
        sleep(1);
        $socket->close("Closing connection after 2 minutes of testing");
    }
    $socket->readline();
    
    /**
     * incomming lines
     *
     * Prevends double handling of lines and handling empty lines
    */
    $data = Irc_Socket::$line . $time;
    if($time === $data || strlen($time)===strlen($data) || $lastLine === $data){
        continue;
    } 
    $lastLine = $data;
    /**
     * Event handler
    */
    
    Irc_Handle::handle();
    Znc_Module::cronjobDeleteAccounts();
}

?>
