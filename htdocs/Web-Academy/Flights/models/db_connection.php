<?php


class Db
{
    private static $instance = NULL;

    private function __construct() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$instance = self::getConnection();
        }

        return self::$instance;
    }

    public static function getConnection()
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

}