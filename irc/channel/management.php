<?php

/**
 * class Irc_Channel_Management
*/
class Irc_Channel_Management {
    
    /**
     * @staticvar array $channel
     * @access protected
     * @todo delete it
    */
    protected static $channel = Array();
    
    /**
     * adds topic of channel to database
     *
     * @access public
     * @param string $channel channel name
     * @param string $topic channel topic
    */
    public function topic($channel,$topic){
        $table = "channels";
        $fields[] = "`value`";
        $where["channel"] = mysql_real_escape_string($channel);
        $where["type"] = "channelInfo";
        $where["option"] = "topic";
        $result = Database_Mysql::select($table,$fields,$where);
        $oldTopic = $result[0]["value"];
        if($oldTopic!=$topic){            
            if($oldTopic){
                $data["value"]=$topic;
                $info["channel"] = $channel;
                $info["type"] = "channelInfo";
                $info["option"] = "topic";
                Database_Mysql::update("channels",$data,$info);
            } else {
                $data["channel"] = $channel;
                $data["type"] = "channelInfo";
                $data["option"] = "topic";
                $data["value"] = $topic;
                Database_Mysql::insert("channels",$data);
            }
        }
        self::$channel[$channel]["topic"] = $topic;
    }
    
    /**
     * adds user to a channel in database
     *
     * @access public
     * @param string $channel channel name
     * @param string $host hostname
    */
    public function addUser($channel,$host){
        
    }
    
    /**
     * removes user to a channel in database
     *
     * @access public
     * @param string $channel channel name
     * @param string $host hostname
    */
    public function removeUser($channel,$host){
    }

    /**
     * modifies users channel data in database
     *
     * @access public
     * @param string $channel channel name
     * @param string $host hostname
     * @param string $item data that needs to be changed
     * @param string $value value of data
    */
    public function modUser($channel,$host,$item,$value){
    }

    /**
     * requests all data from channel
     *
     * @access public
     * @param string $channel channel name
    */
    public function getChanInfo($channel){
    }
}

?>
