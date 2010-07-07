<?php
    includeOnly();
    
    function __autoload($class_name) {
        $class = strtolower(implode("/",explode("_",$class_name)). ".php");
        require_once "../" . $class;
    }
    
    function includeOnly(){
        if(__FILE__ === $_SERVER["SCRIPT_FILENAME"])
            die("not allowed");
    }
    function loginform(){
        echo "<fieldset id='login'>" ;
        echo "\t<legend>Login</legend>" ;
        echo "\t<form action='/' method='post'>" ;
        echo "\t\t<input name='user' type='text' />" ;
        echo "\t\t<input name='pass' type='password' />" ;
        echo "\t\t<input type='submit' value='login' />" ;
        echo "\t</form>" ;
        echo "</fieldset>" ;
    }
    
    function setSessionVars($data){
        foreach($data as $key => $value){
            switch($key){
                case "user":
                case "account":
                    $_SESSION["username"] = $value;
                break;
                case "pass":
                    $_SESSION["password"] = $value;
                break;
                default:
                    $_SESSION[$key] = $value;
                break;
            }
        }
    }
    
    function access(){
        switch($_SESSION["privileges"] . "-"){
            case 0 . "-":
                return "Admin";
            break;
            case 1 . "-":
                return "Helper";
            break;
            case 2 . "-":
                return "Trial";
            break;
            case 3 . "-":
                return "User";
            break;
            case 4 . "-":
                return "User (banned)";
            break;
            default:
                return "Not logged in";
        }
    }
    
    /*
     * function debug
     * @param $arg
     */
    
    function debug($arg) {
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    
    function menu(){
        echo "<div id='menu'><span>Menu</span>";
        echo "<ul>";
        echo "<li>";
        echo "<a href='index.php'>Main</a>";
        echo "</li>";
        echo "<li>";
        echo "<a href='profile.php'>Profile</a>";
        echo "</li>";
/*      echo "<li>";
        echo "<a href='profile.php'>Profile</a>";
        echo "</li>";
*/      echo "</ul>";
        echo "</div>";
    }
?>
