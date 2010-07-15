<?php
session_start();
    if($_SESSION["access"] > 100 && isset($_SESSION["access"])){
        require "../../database/mysql.php";
        new Database_Mysql(true,false);
        if(isset($_GET['command'])){
            $command = $_GET['command'];
            $table = 'commands';
            $select[] = "*";
            $where['command'] = mysql_real_escape_string($command);
            $data = Database_Mysql::select($table,$select,$where);
            if($data['affectedRows']>0){
                echo $data[0]['bind'] . "|" . $data[0]['access'] . "|";
                highlight_file("../../irc/commands/" . str_replace(".","/",$data[0]['bind']) . ".php");
            } elseif($data['affectedRows']==0) {
                echo "Command not found|0|Command not found";
            } else {
                echo "Error in SQL|0|";
                print_r($data);
            }
        }
    }
?>