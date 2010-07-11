<?php
    /*
     * class Irc_User
     * 0                                             1       2        3+
     * 0.0    0.1     0.2
     * :Werring!Werring@Werring.teammanager.TanothNl PRIVMSG #werring :,test3
     */
    class Irc_User  {
        /**
         * nickname of user
         * @access public
         * @return mixed nickname of user or boolean false
        */
        public function nick(){
            $protocol = self::protocol();
            switch($protocol){
                case "PRIVMSG":
                case "NOTICE":
                case "JOIN":
                case "PART":
                $eNick = explode("!",Irc_Format::removeColon(Irc_Socket::$eLine[0]));
                $nick = $eNick[0];
                return $nick;
            break;
            default:
                return false;
            break;
            }
        }
        /**
         * hostname of user
         * @access public
         * @return mixed hostname of user or boolean false
        */
        public function host(){
            $protocol = self::protocol();
            switch($protocol){
                case "PRIVMSG":
                case "NOTICE":
                case "JOIN":
                case "PART":
                $eHost = explode("@",Irc_Format::removeColon(Irc_Socket::$eLine[0]));
                $host = $eHost[1];
                return $host;
            break;
            default:
                return false;
            break;
            }
        }
        
        /**
         * ident of user
         * @access public
         * @return mixed ident of user or boolean false
        */
        public function ident(){
            $protocol = self::protocol();
            switch($protocol){
                case "PRIVMSG":
                case "NOTICE":
                case "JOIN":
                case "PART":
                $eIdent = explode("@",Irc_Format::removeColon(Irc_Socket::$eLine[0]));
                $eIdent = explode("!",$eIdent[0]);
                $ident = $eIdent[1];
                return $ident;
            break;
            default:
                return false;
            break;
            }
        }
        
        /**
         * fullhost of user
         * @access public
         * @return mixed fullhost of user or boolean false
        */
        public function fullHost(){
            $protocol = self::protocol();
            switch($protocol){
                case "PRIVMSG":
                case "NOTICE":
                case "JOIN":
                case "PART":
                $fullHost = Irc_Format::removeColon(Irc_Socket::$eLine[0]);
                return $fullHost;
            break;
            default:
                return false;
            break;
            }
        }
        /**
         * auth of user
         * @access public
         * @return mixed auth of user or boolean false
        */
        public function auth(){
            $host = self::host();
            $table = "IrcUserData";
            $fields[] = "auth";
            $where["host"] = $host;
            $data = Database_Mysql::select($table,$fields,$where);
            if($data["rowsAffected"]>0) 
                return $data[0]["auth"];
            else 
                return false;
            
        }
        
        
        /**
         * nickname of user
         * @access public
         * @return mixed nickname of user or boolean false
        */        
        public function protocol(){
            switch(strtoupper(Irc_Socket::$eLine[1])){
                case "PRIVMSG":
                case "NOTICE":
                case "JOIN":
                case "PART":
                    return strtoupper(Irc_Socket::$eLine[1]);
                break;
                default:
                    return false;
                break;
            }
        }
    }
?>