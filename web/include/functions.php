<?php
/**
 * functionfile for webinterface
*/
    /**
     * make it include only, so no direct calls
    */
    includeOnly();

    /**
     * autoload classes
    */
    function __autoload($class_name) {
        $class = strtolower(implode("/",explode("_",$class_name)). ".php");
        require_once "../" . $class;
    }
    /**
     * die if not included
    */
    function includeOnly(){
        if(__FILE__ === $_SERVER["SCRIPT_FILENAME"])
            die("not allowed");
    }
    /**
     * parse login form
    */
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
    /**
     * Set the session vars needed for the AJAX module
    */
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
    
    /**
     * returns name of your access level
     * @return string acces name
    */
    function access(){
        switch($_SESSION["access"]){
            case ($_SESSION["access"] >= 500):
                return "Master";
            break;
            case ($_SESSION["access"] >= 400 && $_SESSION["access"] < 500):
                return "Admin";
            break;
            case ($_SESSION["access"] >= 300 && $_SESSION["access"] < 400):
                return "Helper";
            break;
            case ($_SESSION["access"] >= 200 && $_SESSION["access"] < 300):
                return "Trial";
            break;
            case ($_SESSION["access"] >= 100 && $_SESSION["access"] < 200):
                return "User";
            break;
            case ($_SESSION["access"] >= 0 && $_SESSION["access"] < 100):
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

    /**
     * parses the menu
    */
    function menu(){
        echo "<div id='menu'><span>Menu</span>";
        echo "<ul>";
        echo "<li>";
        echo "<a href='index.php'>Main</a>";
        echo "</li>";
        echo "<li>";
        echo "<a href='profile.php'>Profile</a>";
        echo "</li>";
        echo "</ul>";
        echo "</div>";
    }
?>
