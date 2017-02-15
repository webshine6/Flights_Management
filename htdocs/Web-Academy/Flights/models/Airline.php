<?php

class Airline
{

    public static function create_airline($name)
    {
        $db = Db::getInstance();

        $req = $db->prepare("
            INSERT INTO airlines (name)
            VALUES (:name)");

        $req->bindParam(':name', $name);
        $req->execute();

        Airline::list_all();

    }


    public static function list_all()
    {
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM airlines');
        $airlines = $req->fetchAll();

        return $airlines;
    }


}