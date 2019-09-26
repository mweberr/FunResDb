<?php



class DBFactory
{
    public static $instance = null;
    
    public static function init($db_ids) {
            try {
                $db_host = $db_ids['db_host'];
                $db_user = $db_ids['db_user'];
                $db_pass = $db_ids['db_pass'];
                $db_name = $db_ids['db_name'];

                self::$instance = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die();
            }

        return self::$instance;
    }
    
    public static function getConnection(){
        return self::$instance;
    }

// Like the constructor, we make __clone private
// so nobody can clone the instance
//
//
    private function __clone() {
        
    }

}

?>
