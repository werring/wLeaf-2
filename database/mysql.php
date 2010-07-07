<?php

    /**
     * class Database_Mysql
    */
    class Database_Mysql {
        
        /**
         * @staticvar string $databaseName
         * @access protected
        */
        protected static $database = "IrcBot";
        
        /**
         * @staticvar resource (mysql) $sql
         * @access protected
        */
        protected static $sql = null;
        
        /**
         * @staticvar boolean $silent
         * @access protected
        */
        protected static $silent = false;
        /**
         * Runs mysql startup
         * 
         * @access public
         * @param boolean $silent don't send echo's (default: false)
         * @param boolean $keepAlive use pconnect (default: true)
        */
        public function __construct($silent=false,$keepAlive=true) {
            if($silent)
                self::$silent = true;
            if(!self::$sql){
                self::start($keepAlive);
            } else {
                if(!self::$silent)
                    Irc_Format::log("MySQL ~ Already done");
            }
        }
        
        /**
         * starts mysqlConnection
         *
         * @access protected
         * @param boolean $keepAlive use pconnect (default: true)
        */
        protected function start($keepAlive=true){
            if($keepAlive){
                self::$sql = mysql_pconnect("mysql.db","werring","TWWT");
            }
            else {
                self::$sql = mysql_connect("mysql.db","werring","TWWT");
            }
            mysql_selectdb(self::$database,self::$sql);
            if(!self::$sql){
                if(!self::$silent)
                    Irc_Format::log("MySQL ~ Error: " . mysql_error(),"ERROR");
                die();
            } else {
                if(!self::$silent)
                    Irc_Format::log("MySQL ~ Done");
            }
        }
        /**
         * sends a query into the mysql database
         *
         * @access public
         * @param string $query mysql query
         * @return resource (mysql) 
        */
        public function sqlQry($query){
            mysql_ping(self::$sql);
            $return = mysql_query($query,self::$sql);
            if(mysql_errno() >= 2000 || !self::$sql){
                Irc_Format::log(mysql_errno() . " " . mysql_error() . " " . var_export(self::$sql,true),"ERROR");
                self::start();
                $return = mysql_query($query,self::$sql);
            }
            return $return;
        }
        
        /**
         * truncate a table
         * 
         * @access public
         * @param string $table the table to be truncated
         * @param boolean false $returnQuery (optional) when true return a mysqlQuery
         * @return string mysqlQuery string, only if $returnQuery is true
        */
        public function clear($table,$returnQuery=false){
            $qry = "TRUNCATE TABLE `".$table."`";
            if($returnQuery){
                return $qry;
            } else {
                if(!self::$silent)
                    Irc_Format::log("clearing table " . $table,"NOTICE");
                self::sqlQry($qry);
            }
        }
        
        /**
         * inserts a row into database
         * 
         * @access public
         * @param string $table name of mysql table
         * @param array $data data to be added in a associative array
         * @param boolean $returnQuery (optional) if set returns the query
         * @return string only if $returnQuery is true
        */
        public function insert($table, $data,$returnQuery=false){
            foreach($data as $field => $value){
                if(strlen($fields) > 0){

                    $fields .= "," . PHP_EOL;
                    $values .= "," . PHP_EOL;
                }
                $fields .= "`". $field ."`";
                $values .= "'". mysql_real_escape_string($value) ."'";
            }
            $qry = "INSERT INTO " . $table . PHP_EOL
                ."(" . $fields . ")". PHP_EOL
                ."VALUES". PHP_EOL
                ."(". PHP_EOL
                . $values . PHP_EOL
                .")";
            if($returnQuery){
                return $qry;
            } else {
                self::sqlQry($qry);
            }
        }
        
        /**
         * selects data from mysql database
         * 
         * @access public
         * @param string $table name of mysql table
         * @param array $fields array of fields that needs to be selected
         * @param array $where (optional) associative array of where clause
         * @param integer $limit (optional) max ammount of rows to be selected
         * @param integer $first (optional) start selecting from this hit
         * @param array $order (optional) order by this
         * @param boolean $returnQuery if set returns the query
         * @return array result of query only if $returnQuery is false
         * @return string only if $returnQuery is true
        */
        public function select($table,$fields,$where=null,$limit=0,$first=0,$order=null,$returnQuery=false){
            if(is_array($fields)){
                $select = implode(",",$fields);
            } else {
                $select = $fields;
            }
            $query = "SELECT " . $select . " FROM " . $table;
            if(null!==$where){
                $query .= " WHERE ";
                foreach($where as $field => $value){
                    if(strlen($WHERE) != 0){
                        if(!isset($where["OR"]) || $where["OR"] != true){
                            $WHERE .= " AND ";
                        }
                        elseif($where["OR"] == true){
                            $WHERE .= " OR ";
                        }
                    }
                    if($field == "OR"){
                        continue;
                    }
                    $WHERE .= "`" . $field . "`='" . $value ."'";
                }
                $query .= $WHERE;
            }
            if(is_array($order)){
                $query .= " ORDER BY ";
                $i = 0;
                foreach($order as $key => $value){
                    if($i > 0){
                        $query.=", ";
                    }
                    $i++;
                        if(!is_numeric($key)){
                            $query .= $key . " " .  $value . " ";
                        }
                        else{
                            $query .= $value . " ASC ";
                        }
                }
            }
            if(0 !== $limit){
                $query.= " LIMIT " . $first . " , " . $limit;
            }
            if($returnQuery){
                return $query;
            }
            $result = self::sqlQry($query);
            if($result){
                while($output = mysql_fetch_assoc($result)){
                    $data[] = $output;
                }
            } else {
                $data["errno"] = mysql_errno();
                $data["error"] = mysql_error();
            }
            $data["affectedRows"] = mysql_affected_rows();
            return $data;
        }
        /**
         * updates mysql table
         * 
         * @access public
         * @param string $table name of mysql table
         * @param array $data associative array of fields that needs to be updated with there new value
         * @param array $where associative array of where clause
         * @param integer $limit (optional) max ammount of rows to be updated
         * @param boolean $returnQuery  (optional) if set returns the query
         * @return array result of query only if $returnQuery is false
         * @return string only if $returnQuery is true
        */
        public function update($table,$data,$where,$limit=1,$returnQuery=false){
            $query = "UPDATE " . $table . PHP_EOL;
            $query.= "SET ";
            foreach($data as $field => $value){
                if(isset($set)){
                   $set.=", " . PHP_EOL; 
                }
                $set.= $field . "='" . $value . "'";
            }
            $query.= $set . PHP_EOL;
            $query.= "WHERE ";
            foreach($where as $field => $value){
                if(strlen($WHERE) != 0){
                    $WHERE .= " AND ";
                }
                $WHERE .= "`" . $field . "`='" . $value ."'";
            }
            $query .= $WHERE;
            if(0 !== $limit){
                $query.= " LIMIT " . $limit;
            }
            if($returnQuery){
                return $query;
            }
            $result = self::sqlQry($query);
           if(!$result){
                $data["errno"] = mysql_errno();
                $data["error"] = mysql_error();
            }
            $data["affectedRows"] = mysql_affected_rows();
            return $data;
        }
        
        /**
         * removes lines from table
         * 
         * @param string $table name of mysql table
         * @param array $where associative array of where clause
         * @param integer $limit (optional) max ammount of rows to be deleted
         * @param boolean $returnQuery if set returns the query
         * @return array result of query only if $returnQuery is false
         * @return string only if $returnQuery is true
        */
        public function remove($table,$where,$limit=1,$returnQuery=false){
            $query = "DELETE FROM " . $table . PHP_EOL;
            $query.= "WHERE ";
                        foreach($where as $field => $value){
                if(strlen($WHERE) != 0){
                    $WHERE .= " AND ";
                }
                $WHERE .= "`" . $field . "`='" . $value ."'";
            }
            $query .= $WHERE;
            if(0 !== $limit){
                $query.= " LIMIT " . $limit;
            }
            if($returnQuery){
                return $query;
            }
            self::sqlQry($query);
            $data["affectedRows"] = mysql_affected_rows();
            return $data;
        }
        
        /**
         * returns a select query
         *
         * @param string $query the select query
        */
        public function advancedSelect($query){
            $result = self::sqlQry($query);
            if($result){
                while($output = mysql_fetch_assoc($result)){
                    $data[] = $output;
                }
            } else {
                $data["errno"] = mysql_errno();
                $data["error"] = mysql_error();
            }
            $data["affectedRows"] = mysql_affected_rows();
            return $data;
        }
        public function startUpCheck(){
            $data = self::advancedSelect("SELECT COUNT(SCHEMA_NAME) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='IrcBot'");
            if($count == 0){
            //    self::sqlQry();
            }
            $resource = self::sqlQry("show tables from IrcBot like 'sets'");
            unset($data);
            while($data[] = mysql_fetch_assoc($resource)){
                
            }
            
            Irc_Format::log(var_export($data,true),"DEBUG");
            die("----" . PHP_EOL);
        }
    }
?>
