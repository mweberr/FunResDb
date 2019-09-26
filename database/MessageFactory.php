<?php



class MessageFactory
{
    public static $message = "Dies ist die erste Nachricht";
    
    public static function init($msg) {
          self::$message = $msg;

    }
    
    public static function getMessage(){
        return self::$message;
    }
    

// Like the constructor, we make __clone private
// so nobody can clone the instance
//
//
    private function __clone() {
        
    }

}

?>
