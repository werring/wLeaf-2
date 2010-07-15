<?php
    session_start();
    require_once("include/functions.php");
    new Database_Mysql(true,false);
    if(!isset($_SESSION['username'])){
        $username = "nobody";
    } else {
        $username = $_SESSION['username'];
    }
?>
<html>
<head>
    <title>TreeAdmin panel [<?=$_SESSION['username']; ?> - <?=access(); ?>]</title>
    <script type="application/x-javascript" src="/scripts/jquery.js"></script>
    <script type="application/x-javascript" src="/scripts/main.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="/styles/main.css" />
    
</head>
<body>
    <?php
    //<!-- fix for my IDE
        echo "<h1>TreeZNC admin panel</h1>";
        echo "<div id='account'><a href='/profile.php'>";
        echo $_SESSION['username'] . "</a> - ";
        echo  access() . " (" .$_SESSION['access'] . ")";
        echo "</div>";
        menu();
        echo "<div id='activeState' /> </div>";
        $table = "commands";
        $select[] = "*";
        $order['bind'] = "ASC";
        $order['command'] = "ASC";
        $data = Database_Mysql::select($table,$select,null,0,0,$order);
        $commands = array();
        $binds    = array();
        foreach($data as $key => $value){
            if(!is_numeric($key)){
                continue;
            }
            $commands[] = $value["command"];
            if(false === array_search($value['bind'],$binds)){
                $binds[]=$value['bind'];
            }
        }
        $dir = "../irc/commands/";
        $dirlist = scandir($dir);
        array_shift($dirlist);array_shift($dirlist);
        foreach($dirlist as $dircontent){
            if(is_dir($dir . $dircontent)){
                $newList[$dircontent] = scandir($dir . $dircontent);
                array_shift($newList[$dircontent]);array_shift($newList[$dircontent]);
            }
        }
        foreach($newList as $main => $files){
            foreach($files as $file){
                $uBinds[] = $main . "." . substr($file,0,-4);
            }
        }
        echo "<table><tr><th>Bind</th><th>Command</th></tr><tr><td valign='top'>";
        echo "<select id='binds'>";
        echo "<optgroup label='used binds'>";
        $i=0;
        foreach($binds as $key => $bind){
            if($bind == 'command'){
                continue;
            }
            $key = array_search($bind,$uBinds);
            if($key !== false){
                unset($uBinds[$key]);
            }
            echo "<option value='" . $bind . "'>". $bind . "</option>";
            $i++;
        }
        if($i == 0){
            echo "<option disabled='disabled'>--none--</option>";
        }
        echo "</optgroup>";
        echo "<optgroup label='unused binds'>";
        $i=0;
        foreach($uBinds as $key => $uBind){
            echo "<option value='" . $uBind . "'>". $uBind . "</option>";            
            $i++;
        }
        if($i == 0){
            echo "<option disabled='disabled'>--none--</option>";    
        }
        echo "</optgroup></select'></td><td>";
        echo "<select style='width: 155px;' id='commands' size='5' onchange='countSelected(this,1);selectCommand(this)'>";
        echo "<option disabled='disabled'></option>";
        echo "</select></td></tr></table>";
        echo "<div id='cmdInfo'></div>";
        echo "<div id='cmdCode' class='code'></div>";
    //-->
?>

</body>
</html>
