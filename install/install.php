<?php
require_once("config.php");
require_once("../database/mysql.php");
new Database_Mysql(true,false);
Database_Mysql::sqlQry('CREATE DATABASE IF NOT EXISTS `IrcBot`');
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `sets`
                            (
                                `id` int(10) unsigned NOT NULL auto_increment,
                                `setting` varchar(255) NOT NULL,
                                `value` varchar(2048) NOT NULL,
                                `setter` varchar(255) NOT NULL,
                                PRIMARY KEY  (`id`),
                                UNIQUE KEY `setting` (`setting`)
                            )'
                        );
Database_Mysql::clear('sets');
foreach($setting as $set => $value){
    $insert['setting'] = $set;
    $insert['value'] = $value;
    Database_Mysql::insert('sets',$insert);
    unset($insert);
}
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `access`
                            (
                                `id` int(10) unsigned NOT NULL auto_increment,
                                `account` varchar(255) NOT NULL,
                                `auth` varchar(255) NOT NULL,
                                `access` int(10) NOT NULL,
                                PRIMARY KEY  (`id`),
                                UNIQUE KEY `account` (`account`)
                            )'
                        );
$master['access'] = 500;
Database_Mysql::clear('access');
Database_Mysql::insert("access",$master);

Database_Mysql::sqlQry('CREATE TABLE `IrcBot`.`commands`
                        (
                            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `bind` VARCHAR( 64 ) NOT NULL ,
                            `file` VARCHAR( 128 ) NOT NULL ,
                            UNIQUE (
                                `bind`
                            )
                        )'
                      );

Database_Mysql::clear('commands');

foreach(scandir('../irc/commands') as $command){
    if(is_dir('../irc/commands' . $command)){
        $dir = $command;
        foreach(scandir('../irc/commands' . $dir) as $command){
            $strpos = strripos($command,'.');
            if($strpos !== false){
                $command = substr($command,0,$strpos);
            }
            $insert = array();
            $insert['bind'] = $command;
            $insert['file'] = $dir . "." . $command;
            echo Database_Mysql::insert('commands',$insert,true) . PHP_EOL;
        }
        continue;
    } elseif($command == '.' || $command == '..'){
        continue;
    }
    
    $strpos = strripos($command,'.');
    if($strpos !== false){
        $command = substr($command,0,$strpos);
    }
    $insert = array();
    $insert['bind'] = $insert['file'] = $command;
    echo Database_Mysql::insert('commands',$insert,true) . PHP_EOL;
}

?>