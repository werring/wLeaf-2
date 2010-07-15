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
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        foreach($_POST as $key => $value){
            if($_SESSION['username']==$_POST['user'] && strlen($_POST['pass']) != 0){
                switch($key){
                    case 'pass':
                        $value = sha1($value);
                        $_SESSION['password'] = $value;
                    case 'user':
                        $login[$key] = strtolower($value);
                    break;
                }
            }
            else {
                $notsaved = true;
            }
        }
        if(count($login) == 2){
            $table = 'AT';
            $update['password'] = $login["pass"];
            $where["account"] = $login["user"];
            $data = Database_Mysql::update($table,$update,$where);
            if($data["affectedRows"]==1){
                $notsaved = false;
                $saved = true;
            } else {
                $notsaved = true;
            }
        } else {
            $notsaved = true;
        }
    } else {
        $notsaved = false;
    }
    if($_SESSION['access'] >= 200 && isset($_SESSION['access'])){
        $query =    "SELECT AT.account,AT.password,access.access FROM `AT` 
                    JOIN access 
                    ON AT.account=access.account
                    WHERE
                    `AT`.`account`='".$_SESSION['username']."'
                    AND
                    `AT`.`password`='".$_SESSION["password"]."'";
        $data = array();
        $data = Database_Mysql::advancedSelect($query);
        if($data["affectedRows"]>0){
            setSessionVars($data[0]);
        } else {
            session_destroy();
            echo "<script>location.reload(true);</script>";
        }
        echo "<h1>TreeZNC admin panel</h1>";
        echo "<div id='account'><a href='/profile.php'>";
        echo $_SESSION['username'] . "</a> - ";
        echo  access() . " (" .$_SESSION['access'] . ")";
        echo "</div>";
        menu();
        echo "<div id='activeState' /> </div>";
        echo "<div id='profile' /><fieldset><legend>" . $_SESSION['username'] . " - " . access() . "</legend>";
        echo "<div id='fieldnames'>";
        echo "<span><label for='user'>Name:</label></span>";
        echo "<span><label for='pass'>Password:</label></span>";
        echo "<span><label for='submit'>Submit:</label></span>";
        echo "</div>";
        echo "<div id='fields'>";
        echo "<form action='/profile.php' method='POST'>";
        echo "<span><input type='text' name='user' value='".$_SESSION['username']."' readonly='readonly' /></span>";
        echo "<span><input type='password' name='pass' value='' /></span>";
        echo "<span><input type='submit' name='submit' value='send' /></span>";
        echo "</form>";
        echo "</div>";
        if($notsaved)
            echo "<span id='profileError'>Data has not been saved</span>";
        if($saved)
            echo "<span id='profileSucces'>Data has been saved</span>";
        echo "</fieldset></div>";
    } else {
        loginform();
    }
    //-->
?>
</body>
</html>
