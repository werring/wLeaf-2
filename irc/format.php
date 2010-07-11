<?php
/*
 * class Irc_Format
 */

class Irc_Format {
    
    /**
     * @staticvar integer length of longest prefix
     * @access protected
    */
    protected static $logPrefixLength = 0;

    /**
     * logs a text
     * 
     * @access public
     * @param string $text text to be logged
     * @param string $prefix (optional) prefix of the text to be logged
     * @return void echo"s $prefix: $text
     * @todo enable real logging instead of echo only
    */
    public function log($text,$prefix="NOTICE"){
        if(self::$logPrefixLength < strlen($prefix)){
            self::$logPrefixLength = strlen($prefix);
        } else {
            $prefix .= str_repeat(" ",self::$logPrefixLength - strlen($prefix));
        }
        
        echo $prefix . ": " . date("H:i:s") . ": " . $text . PHP_EOL;
    }
    
    /**
     * removes colon if it is the first character of a line / word
     *
     * @access public
     * @param string $string line to delete colon from
     * @return string line without colon
    */
    public function removeColon($string){
        if(substr($string,0,1) === ":"){
            return substr($string,1);
        }
        return $string;
    }
    /**
     * format names to be easier to handle
     * 
     * @access public
     * @return array names with appended prefix as "name,prefix"
    */
    public function formatNames(){
        $line = Irc_Socket::$line;
        $eLine = explode(":",$line);
        $names = $eLine[2];
        $names = explode(" ",$names);
        foreach($names as $key => $name){
            $names[$key] = self::movePrefix($name);
        }
        return $names;
    }
    /**
     * moves prefix (@ / +) if its the first char and then appends it as ,prefix
     * 
     * @access public
     * @param string $name name with the to be moved prefix
     * @return string name with the moved prefix as "name,prefix"
    */
    public function movePrefix($name){
        if(substr($name,0,1) === "@" || substr($name,0,1) === "+"){
            
            return trim(substr($name,1)) . "," . substr($name,0,1);
        }
        return trim($name) . ",";
    }
    
    
    public function BOLD(){
        return chr(0x2);
    
    }
    public function UNDERLINED(){
        return chr(0x1F);
    
    }
    public function REVERSED(){
        return chr(0x16);
    
    }
    public function NORMAL(){
        return chr(0x0F);
    }
    
    /**
     * returns an IRC color token with the specified color
     *
     * @access public
     * @param integer $foreground (optional) foreground color (0-15)
     * @param integer $background (optional) b coackgroundlor (0-15)
     * @return
    */
    public function COLOR($foreground=null,$background=null){
        $return = chr(0x03);
        if($foreground != null){
            $foreground = $foreground%16;
            if($foreground < 10){
                $return .= "0" . $foreground . ",";
            } elseif($foreground < 16){
                $return .= $foreground . ",";
            } 
        }
        if($background != null && $foreground == null){
            $return .= "01,";
        }
        if($background != null){
            $background = $background%16;
            if($background < 10){
                $return .= "0" . $background;
            } elseif($background < 16){
                $return .= $background;
            } 
        }
        return $return;
    }
    
    
    /**
     * returns closest word that is in the array, with, optionaly an percentage of the match
     *
     * @abstract public
     * @param string $input word to be matched
     * @param array $words array with words $input will be matched against
     * @param integer $percent returns the percentage of the match
     * @return string closest word it found in the array
    */
    public function closest_word($input, $words, &$percent = null) {
        $shortest = -1;
        foreach ($words as $word) {
          $lev = levenshtein($input, $word);
    
          if ($lev == 0) {
            $closest = $word;
            $shortest = 0;
            break;
          }
    
          if ($lev <= $shortest || $shortest < 0) {
            $closest  = $word;
            $shortest = $lev;
          }
        }

        $percent = 1 - levenshtein($input, $closest) / max(strlen($input), strlen($closest));

        return $closest;
    }
}
?>
