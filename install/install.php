<?php
/**
 * import all required files
*/
require_once "config.php" ;
require_once "../database/mysql.php" ;
require_once "zncconf.php";
/**
 * open silent and none persistent database connection
*/
new Database_Mysql(true,false);
echo 'Creating database IrcBot' . PHP_EOL;
/**
 * create database IrcBot if it doen't exists
*/
Database_Mysql::sqlQry('CREATE DATABASE IF NOT EXISTS `IrcBot`');
sleep(1);
Database_Mysql::sqlQry('use IrcBot');
echo 'Creating table \'sets\'' . PHP_EOL;
/**
 * Creates table "set"
 * @param unsigned interger(10) id primary key and auto_increment
 * @param string(255) setting unique key
 * @param string(2048) value
 * @param string(255) setter
*/
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
/**
 * Inserts settings from config file in table
*/
foreach($settings as $set => $value){
    $insert['setting'] = $set;
    $insert['value'] = $value;
    Database_Mysql::insert('sets',$insert);
    unset($insert);
}
sleep(1);
echo 'Creating table \'access\'' . PHP_EOL;
/**
 * Creates table "access"
 * @param unsigned interger(10) id primary key and auto_increment
 * @param string(255) account unique key
 * @param int setter
 * @param boolean banned
*/
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`access`
                            (
                                `id` int(10) unsigned NOT NULL auto_increment,
                                `account` varchar(255) NOT NULL,
                                `auth` varchar(255) NOT NULL,
                                `access` int(10) NOT NULL,
                                `banned` tinyint(1) NOT NULL,
                                PRIMARY KEY  (`id`),
                                UNIQUE KEY `account` (`account`)
                            )'
                        );
$master['access'] = 500;
Database_Mysql::clear('access');
/**
 * Inserts users into tablr
*/
Database_Mysql::insert("access",$master);
echo "Inserting " . $master['account'] . " with an access of " . $master['access'] . PHP_EOL;
foreach($zncConf['users'] as $key => $user){
    unset($fields,$where,$select);
    $fields[] = "*";
    $where['account'] = $user;
    $select = Database_Mysql::select('access',$fields,$where);
    if($select['affectedRows'] == 1){
        //case sensitive check
        if($select[0]['account']==$user){
            continue;
        }
    }
    $data['account'] = $data['auth'] = $user;
    if($zncConf['user'][$user]['Admin'] == "true"){
        $data['access'] = 400;
    } else {
        $data['access'] = 100;
    }
    echo "Inserting " . $data['account'] . " with an access of " . $data['access'] . PHP_EOL;
    Database_Mysql::insert('access',$data);
}
sleep(1);
echo 'Creating table \'commands\'' . PHP_EOL;
/**
 * Creates table "commands"
 * @param unsigned interger(10) id primary key and auto_increment
 * @param string(64) command unique key
 * @param string(128) bind
 * @param int access
*/
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`commands`
                        (
                            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `command` VARCHAR( 64 ) NOT NULL ,
                            `bind` VARCHAR( 128 ) NOT NULL ,
                            `access` INT NOT NULL ,
                            UNIQUE (
                                `command`
                            )
                        )'
                      );
Database_Mysql::clear('commands');
/**
 * Inserts known commands into table
*/
foreach(scandir('../irc/commands/') as $command){
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
            $insert['access'] = 300;
            Database_Mysql::insert('commands',$insert);
            printf("Binding %s to %s" . PHP_EOL,$insert['bind'],$insert['command']);
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
    $insert['bind'] = $insert['command'] = $command;
    printf("Binding %s to %s" . PHP_EOL,$insert['bind'],$insert['command']);
    Database_Mysql::insert('commands',$insert);
}

sleep(1);
echo 'Creating table \'IrcUserData\'' . PHP_EOL;
/**
 * Creates table "set"
 * @param unsigned interger(10) id primary key and auto_increment
 * @param string(50) ident
 * @param string(50) host
 * @param string(50) auth
*/
Database_Mysql::sqlQry('CREATE TABLE IF NOT EXISTS `IrcBot`.`IrcUserData` ( 
                            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `ident` VARCHAR( 50 ) NOT NULL ,
                            `host` VARCHAR( 50 ) NOT NULL ,
                            `auth` VARCHAR( 50 ) NOT NULL
                        )
                        ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci');

echo "Done" . PHP_EOL;