<?php
require_once("config.php");
require_once("../database/mysql.php");
new Database_Mysql(true,false);
echo 'Creating database IrcBot' . PHP_EOL;
Database_Mysql::sqlQry('CREATE DATABASE IF NOT EXISTS `IrcBot`');
sleep(1);
echo 'Creating table \'sets\'' . PHP_EOL;
Database_Mysql::sqlQry('use IrcBot');
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`sets`
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
foreach($settings as $set => $value){
    $insert['setting'] = $set;
    $insert['value'] = $value;
    Database_Mysql::insert('sets',$insert);
    unset($insert);
}
sleep(1);
echo 'Creating table \'access\'' . PHP_EOL;
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`access`
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

sleep(1);
echo 'Creating table \'commands\'' . PHP_EOL;
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`commands`
                        (
                            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `command` VARCHAR( 64 ) NOT NULL ,
                            `bind` VARCHAR( 128 ) NOT NULL ,
                            UNIQUE (
                                `command`
                            )
                        )'
                      );
Database_Mysql::clear('commands');

foreach(scandir('../irc/commands') as $command){
    if(is_dir('../irc/commands/' . $command)){
        $dir = $command;
        if($dir == '.' || $dir == '..'){
            continue;
        }
        foreach(scandir('../irc/commands/' . $dir) as $command){
            if($command == '.' || $command == '..'){
                continue;
            }
            $strpos = strripos($command,'.');
            if($strpos !== false){
                $command = substr($command,0,$strpos);
            }
            $insert = array();
            $insert['command'] = $command;
            $insert['bind'] = $dir . "." . $command;
            Database_Mysql::insert('commands',$insert);
            printf("Binding %s to %s" . PHP_EOL,$insert['bind'],$insert['command']);        }
        continue;
    } elseif($command == '.' || $command == '..'){
        continue;
    }
    
    $strpos = strripos($command,'.');
    if($strpos !== false){
        $command = substr($command,0,$strpos);
    }
    $insert = array();
    $insert['bind'] = $insert['command'] = $command;
    printf("Binding %s to %s" . PHP_EOL,$insert['bind'],$insert['command']);
    Database_Mysql::insert('commands',$insert);
}

sleep(1);
echo 'Creating table \'IrcUserData\'' . PHP_EOL;
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`IrcUserData` ( 
                            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `ident` VARCHAR( 50 ) NOT NULL ,
                            `host` VARCHAR( 50 ) NOT NULL ,
                            `auth` VARCHAR( 50 ) NOT NULL
                        )
                        ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci');
?>