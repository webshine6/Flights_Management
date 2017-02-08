<?php


/**
 * Singleton Pattern
 * getConnection() returning an instance of PDO connection
 *
 * @author ramona
 */
final class PDOConnection
{
    /**
     * singleton instance
     *
     * @var PDOConnection
     */
    private static $instance;

    /**
     * Call this method to get instance
     *
     * @return PDOConnection
     */
    public static function getInstance()
    {
        if (!isset( self::$instance  ))
        {
            self::$instance = new PDOConnection();
        }

        return self::$instance;

    }

    // disallow cloning of an instance.
    private function __construct()
    {
    }

    /**
     * Create a new database connection using the dsn
     * and credentials provided (username, password)
     *
     * @param $dsn string $dsn The DSN to database
     * @param $username string $username Database username
     * @param $password string $password Database password
     * @return PDO connection to the database
     * @throws PDOException
     * @throws Exception
     */
    public function getConnection()
    {
        // Get the path to config.ini
        $iniFile = __DIR__.'/../config/config.ini';
        try {
            // read the .ini file and create an associative array
            $db_config = parse_ini_file($iniFile, true);
        }
        catch (Exception $e) {
            die('Missing INI file: ' . $iniFile);
        }

        // use info in .ini file to create string connection
        $host      = $db_config['host'];
        $username  = $db_config['user'];
        $password  = $db_config['pass'];
        $type      = $db_config['type'];
        $charset   = $db_config['charset'];
        $dbname    = $db_config['dbname'];

        $dns = "$type:host=$host;dbname=$dbname;charset=$charset";

        $link = null;

        try{
            $link = new PDO($dns, $username, $password);
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $link->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $link->setAttribute(PDO::ATTR_PERSISTENT, false);
            $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $link;
        }
        catch (PDOException $e){
            echo 'Unable to connect to database: '.$e->getMessage();
        }
    }


    // disallow cloning instance
    private function __clone()
    {

    }

    // disallow serialize instance
    private function __sleep()
    {

    }

    // disallow unserialize instance
    private function __wakeup()
    {

    }

}