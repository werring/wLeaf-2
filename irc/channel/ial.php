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
        
        protected function addNick($channel,$nick, $ident, $host){
            if(self::$networkDefined){
                $data['nick']    = $nick;
                $data['ident']   = $ident;
                $data['host']    = $host;
                $data['channel'] = $channel;
                $data['network'] = self::$network;
                Database_Mysql::insert('InternalAdressList',$data);
                return true;
            } else {
                return false;
            }
        }
        protected function changeNick($oldNick,$newNick){
            if(self::$networkDefined){
                $data['nick']=$newNick;
                $where['nick'] = $oldNick;
                $return = Database_Mysql::update('InternalAdressList',$data,$where,0);
                if($return['errorno'] === 0){
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        protected function quitNick($nick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        protected function removeNick($channel,$nick){
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
        public function getFullHost($nick){
            if(self::$networkDefined){
                
            } else {
                return false;
            }
        }
        public function handleIAL(){
            if(self::$networkDefined){
                switch(strtoupper(Irc_Socket::$eLine[1])){
                    case 'JOIN':
                        if(Irc_User::fullHost()=== false || Irc_Channel::getChan()=== false){ 
                            $fullhost = Irc_Format::removeColon(Irc_Socket::$eLine[0]);
                            $channel  = Irc_Format::removeColon(Irc_Socket::$eLine[2]);
                            $eData    = explode("!",$fullhost);
                            $nick     = array_shift($eData);
                            $eData    = explode("@",array_shift($eData));
                            $ident    = array_shift($eData);
                            $host     = array_shift($eData);
                            unset($eData);
                        } else {
                            $fullhost = Irc_User::fullHost();
                            $channel  = Irc_Channel::getChan();
                            $nick     = Irc_User::nick();
                            $ident    = Irc_User::ident();
                            $host     = Irc_User::host();
                        }
                        $return = self::addNick($channel,$nick,$ident,$host);
                    break;
                    case 'PART':
                    break;
                    case 'KICK':
                    break;
                    case 'QUIT':
                    break;
                    case 'NICK':
                        $eData   = explode("!",Irc_Socket::$eLine[0]);
                        $oldNick = array_shift($eData);
                        $newNick = Irc_Format::removeColon(Irc_Socket::$eLine[2]);
                        $return = self::changeNick($oldNick,$newNick);
                    break;
                    case '352':
                        $channel = Irc_Socket::$eLine[3];
                        $nick    = Irc_Socket::$eLine[7];
                        $ident   = Irc_Socket::$eLine[4];
                        $host    = Irc_Socket::$eLine[5];
                        $return  = self::addNick($channel,$nick,$ident,$host);
                    break;
                }
                if($return === false){
                    Irc_Format::log("An error occured in the Internal Adress List","DEBUG");
                }
                return $return;
            } else {
                return false;
            }
        }
    }
?>