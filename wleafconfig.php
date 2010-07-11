<?php

class WleafConfig {
    
    /**
     * @staticvar array $config stores main configuration
    */
    protected static $config = Array();
    protected static $settings = Array();
    
    /**
     * constructor
     * imports the mysql sets into an array
    */
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

    /**
     * returns the setting given as parameter
     * @access public
     * @param string $name name of the setting
     * @return mixed setting or boolean false
    */    
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
    
    /**
     * returns all settings
     * @access public
     * @return array all settings stored in an array
    */
    public function getSettings(){
        return self::$settings;
    }
    
    /**
     * set a setting to a new value
     *
     * @access public
     * @param string $name name of the setting
     * @param string $value value of the setting
     * @return integer affected rows
    */
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