<?php



/**
 * Create a new database connection
 *
 * The PDO options:
 * PDO::ATTR_ERRMODE enables exceptions for errors
 * PDO::ATTR_PERSISTENT disables persistent connections
 * @return PDO - database connection
 */
function connect_database()
{

    // read the .ini file and create an associative array
    $db_config = parse_ini_file('config.ini',true);

    // use the info in .ini file to create string connection
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