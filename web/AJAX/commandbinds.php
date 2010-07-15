<?php
session_start();
    if($_SESSION["access"] > 100 && isset($_SESSION["access"])){
        require "../../database/mysql.php";
        new Database_Mysql(true,false);
        if(isset($_GET['bind'])){
            $bind = $_GET['bind'];
            $table = 'commands';
            $select[] = "*";
            $where['bind'] = mysql_real_escape_string($bind);
            $sort['command'] = "ASC";
            $data = Database_Mysql::select($table,$select,$where,0,0,$sort);
            if($data['affectedRows']>0){
                foreach($data as $key => $value){
                    if(is_numeric($key)){
                        echo "<option value='".$value['command']."'>".$value['command']."</option>";
                    }
                }
            } elseif($data['affectedRows']==0) {
                echo "<option disabled='disabled'>No commands</option>";
                } else {
                print_r($data);
            }
        }
    }
?>