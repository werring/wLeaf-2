<?php
    /*
     * class IAL
     */
    
    class IAL {
        
        static protected $network = null;
        static protected $networkDefined = false;
        /*
         * __construct()
         * @param string $network network name
         * @access public
         */
        
        public function __construct($network) {
            self::$network = $network;
            self::$networkDefined = true;
        }
        
        public function add($channel,$nick, $ident, $host){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        public function changeNick($oldNick,$newNick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        public function getFullHost($nick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        public function quitNick($nick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        public function partChannel($channel,$nick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }        
        }
        public function channelList($channel){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
    }
?>