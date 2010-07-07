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
    <script type="application/x-javascript" src="/scripts/log.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="/styles/main.css" />
    
</head>
<body>
    <?php
    //<!-- fix for my IDE
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        foreach($_POST as $key => $value){
            switch($key){
                case 'pass':
                    $value = sha1($value);
                case 'user':
                    $login[$key] = strtolower($value);
                break;
            }
        }
        if(count($login) == 2){
            $query =    "SELECT AT.account,AT.password,accounts.privileges FROM `AT` 
                        JOIN accounts 
                        ON AT.account=accounts.account
                        WHERE
                        `AT`.`account`='".$login['user']."'
                        AND
                        `AT`.`password`='".$login["pass"]."'";
            $data = Database_Mysql::advancedSelect($query);
            if($data["affectedRows"]==1){
                setSessionVars($data[0]);
            }
        } else {
            echo count($login) . PHP_EOL;
        }        
    }
    if($_SESSION['privileges'] <= 3 && isset($_SESSION['privileges'])){
        $query =    "SELECT AT.account,AT.password,accounts.privileges FROM `AT` 
                    JOIN accounts 
                    ON AT.account=accounts.account
                    WHERE
                    `AT`.`account`='".$_SESSION['username']."'
                    AND
                    `AT`.`password`='".$_SESSION["password"]."'";
        $data = Database_Mysql::advancedSelect($query);
        if($data["affectedRows"]==1){
            setSessionVars($data[0]);
        }
        echo "<h1>TreeZNC admin panel</h1>";
        echo "<div id='account'><a href='/profile.php'>";
        echo $_SESSION['username'] . "</a> - ";
        echo  access() . " (" .$_SESSION['privileges'] . ")";
        echo "</div>";
        menu();
        echo "<div id='activeState' /> </div>";
        echo "<div id='logView' > </div>";
        echo "<div id='logOptions' > </div>";
    } else {
        loginform();
    }
    //-->
?>
</body>
</html>
