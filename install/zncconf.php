<?php

$fp = fopen($znc['znc.conf'],'r');
$page = fread($fp,filesize($znc['znc.conf']));
fclose($fp);
$lines = explode("\n",$page);
foreach($lines as $linenumber => $line){
    $line = trim($line);
    $linenumber++;
    switch($line){
        case (preg_match("/^\w*\s*\=\s/",$line) && !$user):
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
            $user = true;
            $uname = substr($line,6,strlen($line)-7);
            $zncConf['users'][] = $uname;
        break;
        case (0!= preg_match("/^<\/User>$/",$line)):
            $user = $chan = false;
            $uname = null;
        break;
        case (preg_match("/^\w*\s*\=\s/",$line) && $user == true && !$chan):
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
            $chan = true;
            $channame = substr($line,6,strlen($line)-7);
            $zncConf['user'][$uname]['chans'][] = $channame;
        break;
        case (preg_match("/^\w*\s*\=\s/",$line) && $user == true && $chan==true):
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
        case (0!= preg_match("/^<\/Chan>$/",$line)):
            $chan = false;
        break;
    }
}


?>