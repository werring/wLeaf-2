<?php

class WleafConfig {
    
    /**
     * @staticvar array $config stores main configuration
    */
    protected static $config = Array();
    protected static $settings = Array();
    
    
    public function __construct() {
        if(!isset(self::$config["init"])){
            $table = "sets";
            $select[] = "*";
            $data = Database_Mysql::select($table,$select);
            foreach($data as $key=>$value){
                if(is_numeric($key)){
                    self::$settings[$value["setting"]] = $value["value"];
                }
            }
            Irc_Format::log("Configuration ~ Done");
            self::$settings["startupTime"] = time();
            self::$config["init"] = true;
        } else {
            Irc_Format::log("config already loaded");
        }
    }
    
    public function getConf($name=null){
        if(null===$name){
            return false;
        } else {
            if(isset(self::$settings[$name])){
                return self::$settings[$name];
            } else {
                return false;
            }
        }
    }
    
    public function getSettings(){
        return self::$settings;
    }
    
    public function setConf($name=null,$value=null){
        if(isset(self::$settings[$name]) && $value != self::getConf($name)){
            $table = "sets";
            $data["value"] = $value;
            $data["setter"] = Irc_User::fullHost();
            $where["name"] = $name;
            $return = Database_Mysql::update($table,$data,$where,1);
            self::$settings[$name] = $value;
            return $return["affectedRows"];
        } else {
            return "0";
        }
    }
}



?>