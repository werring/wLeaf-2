<?php
/**
 * opens the ZNC config file
*/
$fp = fopen($znc['znc.conf'],'r');
$page = fread($fp,filesize($znc['znc.conf']));
fclose($fp);
$lines = explode("\n",$page);
/**
 * run trough all lines
*/
foreach($lines as $linenumber => $line){
    $line = trim($line);
    $linenumber++;
    switch($line){
        case (preg_match("/^\w*\s*\=\s/",$line) && !$user):
        //Global config lines
            $eLine = explode("=",$line);
            $value = trim($eLine[1]);
            $conf  = trim($eLine[0]);
            unset($eLine);
            if($conf != ""){
                if(!isset($zncConf[$conf])){
                    $zncConf[$conf] = $value;
                } elseif(!is_array($zncConf[$conf])) {
                    $zncConf[$conf] = array($zncConf[$conf]);
                    $zncConf[$conf][] = $value;
                } else {
                    $zncConf[$conf][] = $value;
                }
            }
        break;
        case (0!= preg_match("/^<User\s[a-zA-Z][a-zA-Z0-9_.@-]*>$/",$line)):
        //User tag opens here
            $user = true;
            $uname = substr($line,6,strlen($line)-7);
            $zncConf['users'][] = $uname;
        break;
        case (0!= preg_match("/^<\/User>$/",$line)):
        //User tag closes here
            $user = $chan = false;
            $uname = null;
        break;
        case (preg_match("/^\w*\s*\=\s/",$line) && $user == true && !$chan):
        //User options
            $eLine = explode("=",$line);
            $value = trim($eLine[1]);
            $conf  = trim($eLine[0]);
            unset($eLine);
            if($conf != ""){
                if(!isset($zncConf['user'][$uname][$conf])){
                    $zncConf['user'][$uname][$conf] = $value;
                } elseif(!is_array($zncConf['user'][$uname][$conf])) {
                    $zncConf['user'][$uname][$conf] = array($zncConf['user'][$uname][$conf]);
                    $zncConf['user'][$uname][$conf][] = $value;
                } else {
                    $zncConf['user'][$uname][$conf][] = $value;
                }
            }
        break;
        case (0!= preg_match("/^<Chan\s#[^(\s|,)]*>$/",$line) && $user == true):
        //Chan tag opens here
            $chan = true;
            $channame = substr($line,6,strlen($line)-7);
            $zncConf['user'][$uname]['chans'][] = $channame;
        break;
        case (0!= preg_match("/^<\/Chan>$/",$line)):
        //Chan tag opens here
            $chan = false;
        break;
        case (preg_match("/^\w*\s*\=\s/",$line) && $user == true && $chan==true):
        //Channel options
            $eLine = explode("=",$line);
            $value = trim($eLine[1]);
            $conf  = trim($eLine[0]);
            unset($eLine);
            if($conf != ""){
                if(!isset($zncConf['user'][$uname]['chan'][$channame][$conf])){
                    $zncConf['user'][$uname]['chan'][$channame][$conf] = $value;
                } elseif(!is_array($zncConf['user'][$uname]['chan'][$channame][$conf])) {
                    $zncConf['user'][$uname]['chan'][$channame][$conf] = array($zncConf['user'][$uname]['chan'][$channame][$conf]);
                    $zncConf['user'][$uname]['chan'][$channame][$conf][] = $value;
                } else {
                    $zncConf['user'][$uname]['chan'][$channame][$conf][] = $value;
                }
            }
        break;
    }
}
?>