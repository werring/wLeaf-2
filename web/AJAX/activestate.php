<?php
session_start();
if($_SESSION["privileges"] <= 3 && isset($_SESSION["privileges"])){
$line = strlen(exec("top -n 1 -b -u wleaf | grep 'php'"));    
    if($line == 0){
        echo "TreeAdmin is offline|0" . PHP_EOL;
    } else {
        echo "TreeAdmin is online|1" . PHP_EOL;
    }
} else {
    echo "No access to the online state of TreeAdmin|2";
}
?>