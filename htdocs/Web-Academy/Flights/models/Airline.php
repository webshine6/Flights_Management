<?php

class Airline
{

    /**
     * Create new Airline
     *
     * @param $name string - the name of airline
     */
    public static function create_airline($name)
    {
       try {
           $db = Db::getInstance();

           $req = $db->prepare("
            INSERT INTO airlines (name)
            VALUES (:name)");

           $req->bindParam(':name', $name);
           $req->execute();
       }catch (PDOException $e) {
           echo 'Create Airline Exception. '.$e->getMessage();
       }
    }

    /**
     * Get All Airlines
     * @return array airlines
     */
    public static function list_all()
    {
        $airlines = '';
        try {
            $db = Db::getInstance();
            $req = $db->query('SELECT id_airline, name FROM airlines');
            $airlines = $req->fetchAll();
        }catch (PDOException $e) {
            echo 'Get All Airlines Exception. '.$e->getMessage();
        }
        return $airlines;
    }

}