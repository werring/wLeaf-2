<?php

/**
 * import all required files
*/
require_once "config.php";
require_once "../database/mysql.php" ;
require_once "../irc/format.php";
require_once "zncconf.php";
/**
 * open silent and none persistent database connection
*/
new Database_Mysql(true,false);
Database_Mysql::sqlQry('use IrcBot');

//Run trough users
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
    //Give user 100 or 400 access
    if($zncConf['user'][$user]['Admin'] == "true"){
        $data['access'] = 400;
    } else {
        $data['access'] = 100;
    }
    echo "Inserting " . $data['account'] . " with an access of " . $data['access'] . PHP_EOL;
    Database_Mysql::insert('access',$data);
}