<?php
session_start();
include("../include/functions.php");
/**
 * testversion of logViewer
*/
if($_SESSION['privileges'] < 3 && isset($_SESSION['privileges'])){
        $red = array();
    if($_SESSION['privileges']!= 0){
        $red[] = 'tree-admin';
        $red[] = 'werring-test';
    }
    $file = file_get_contents("/home/wleaf/wleafv2/wLeaf.log");
    $linesPre = explode("\n",$file);
    $maxLines = 500;
    foreach($linesPre as $lineNumber => $text){
        if(isset($_GET['search'])){
            if(1!=preg_match("/".$_GET['search'] ."/i",$text)){
                continue;
            }
        }
        $eText = explode(":",$text,5);
        switch(trim(strtolower($eText[0]))){
            case 'notice':
                if(!isset($_GET['console']) || $_GET['console']=="1"){
                        $lines[] = $text;
                }
            break;
            case 'input':
                if(!isset($_GET['input']) || $_GET['input']=="1"){
                    if(preg_match("/pong :znc/i",trim($eText[4]))!=1 || (isset($_GET['ping']) && $_GET['ping']=='1'))
                        $lines[] = $text;
                }
            break;
            case 'output':
                
                switch($eText[4]){
                    case (preg_match("/^ping :znc$/i",trim($eText[4])) == 1):
                        if(isset($_GET['ping']) && $_GET['ping']=='1'){
                            $lines[] = $text;
                        }
                    break;
                    case (preg_match("/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#/i",trim($eText[4]))==1):
                        if(is_array($_GET['channel'])){
                            foreach($_GET['channel'] as $chan){
                                $regex= "/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#".$chan."\s/i";
                                if(preg_match($regex,trim($eText[4]))==1 && !is_numeric(array_search($chan,$red))){
                                    $lines[] = $text;
                                }
                            }
                        } elseif(isset($_GET['channel'])) {
                            $chan = $_GET['channel'];
                            $regex= "/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#".$chan."\s/i";
                            if(preg_match("/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#".$chan."\s/i",trim($eText[4]))==1 && !is_numeric(array_search($chan,$red))){
                                $lines[] = $text;
                            }
                        }
                        if(!isset($_GET['channel'])){
                            foreach($red as $chan){
                                $regex= "/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#".$chan."\s/i";
                                if(preg_match("/^:\w*!\w*@([\w\d]?[\.:]?){0,}\s(notice|mode|privmsg|join)\s#".$chan."\s/i",trim($eText[4]))!=1){
                                    $lines[] = $text;
                                }
                            }
                        }
                    break;
                    default:
                        $lines[] = $text;
                    break;
                }
            break;

        }
    }
    if(is_array($lines)){
        $totalLines = count($lines);
        foreach($lines as $lineNumber => $text){
            if($lineNumber >= ($totalLines-$maxLines) && strlen($text) != 0){
                echo ( (1+$lineNumber).":". filter_var($text,FILTER_SANITIZE_SPECIAL_CHARS) . "<br />");
            } else {
                continue;
            }
        }
    }
//print_r($lines);
} else {
    echo 'No access to the logs of TreeAdmin';
}
?>