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
    if($_SESSION['access'] >= 200 && isset($_SESSION['access'])){
        $query =    "SELECT AT.account,AT.password,access.access FROM `AT` 
                    JOIN access 
                    ON AT.account=access.account
                    WHERE
                    `AT`.`account`='".$_SESSION['username']."'
                    AND
                    `AT`.`password`='".$_SESSION["password"]."'";
        $data = Database_Mysql::advancedSelect($query);
        if($data["affectedRows"]==1){
            setSessionVars($data[0]);
        } else {
            unset($_SESSION);
            echo "<script>location.reload(true);</script>";
        }
        echo "<h1>TreeZNC admin panel</h1>";
        echo "<div id='account'><a href='/profile.php'>";
        echo $_SESSION['username'] . "</a> - ";
        echo  access() . " (" .$_SESSION['access'] . ")";
        echo "</div>";
        menu();
        echo "<div id='activeState' /> </div>";
        $table = "help";
        $select[] = "subject";
        $select[] = "language";
        $data = Database_Mysql::select($table,$select);
        debug($data);
    } else {
        loginform();
    }
    //-->
?>
</body>
</html>
