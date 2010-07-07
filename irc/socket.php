<?php
/*
 * class irc_socket
 */

class Irc_Socket {
    
    /**
     * @staticvar resource socket link
     * @access protected
    */
    protected static $socket = null;
    /**
     * @staticvar string complete IRC line of last reply
     * @access public
    */
    public static $line = "";
    /**
     * @staticvar array complete IRC line of last reply exploded on " "
     * @access public
    */
    public static $eLine;
    /**
     * @staticvar boolean are we connected to IRC?
     * @access public
    */
    public static $connected = DISCONNECTED;
    
    /**
     * @staticvar boolean do we want to keep trying to connect?
     * @access public
    */
    public static $reconnection = true;
    
    /**
     * Creates the socket connection
     *
     * @access public
    */
    public function __construct() {
        if(null === self::$socket){
            if(!self::$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) {
                Irc_Format::log("Irc Connection ~ @1 " . socket_strerror(socket_last_error()) );
                self::$reconnection = false;
                self::$connected = DISCONNECTED;
            }
            if(!socket_bind(self::$socket,WleafConfig::getConf("hostname"))) {
                Irc_Format::log(" Irc Connection ~ @2 " . socket_strerror(socket_last_error(self::$socket)));
                self::$reconnection = false;
                self::$connected = DISCONNECTED;
            }
            if(!socket_connect(self::$socket,WleafConfig::getConf("server"),WleafConfig::getConf("poort"))) {
                Irc_Format::log("Irc Connection ~ @3 " . socket_strerror(socket_last_error(self::$socket)) );
                self::$connected = DISCONNECTED;
            }
            self::$connected=CONNECTED;
            Irc_Format::log("Irc Connection ~ Done");
        }
    }
    
    /**
     * reads IRC replies
     *
     * @access public
    */
    public function readline(){
        $data = @socket_read(self::$socket,65000,PHP_NORMAL_READ);
        self::$line = trim($data);
        if(strlen(self::$line) != 0){
            Irc_Format::log(self::$line,"OUTPUT");
            self::$eLine = explode(" ",self::$line);
        }       
    }
    
    /**
     * writes a line to IRC
     *
     * @access public
     * @param string $text text to write to IRC
    */
    public function write($text){
        Irc_Format::log($text,"INPUT");
	socket_write(self::$socket, $text.PHP_EOL);
    }
    
    /**
     * closes the IRC connection
     *
     * @param string $msg (optional) Message to send to log
     * @param string $type (optional) Prefix of message for log
    */
    public function close($msg = "Closing connection",$type="NOTICE"){
        self::$connected = DISCONNECTED;
        self::$reconnection = false;
        socket_close(self::$socket);
        self::$socket = null;
        
        Irc_Format::log($msg,$type);
    }

}
?>
