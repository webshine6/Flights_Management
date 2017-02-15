<?php

class Destination
{
    public static function get_airports()
    {
        $db = Db::getInstance();
        $req = $db->query('SELECT id_airport, name
                           FROM airports
                           ORDER BY name');
        $airports = $req->fetchAll();
        return $airports;
    }

    public static function list_all()
    {
        $db = Db::getInstance();
        $req = $db->query('
        SELECT dest1.id_airport_from, a1.name as namefrom, dest1.id_airport_to, a2.name as nameto, dest1.price, dest1.id_destination
        FROM destinations AS dest1
        JOIN airports AS a1
        ON dest1.id_airport_from = a1.id_airport
        JOIN airports AS a2
        ON dest1.id_airport_to = a2.id_airport 
        ORDER BY dest1.id_destination;');
        $destinations = $req->fetchAll();

        return $destinations;
    }


    // TODO
    function create_destination($id_airport_from, $id_airport_to, $price) {
        $db = Db::getInstance();

        $req = $db->prepare("
            INSERT INTO destinations (id_airport_from, id_airport_to, price)
            VALUES (:id_airport_from, :id_airport_to, :price)");

        $req->bindParam(':id_airport_from', $id_airport_from, PDO::PARAM_INT);
        $req->bindParam(':id_airport_to', $id_airport_to, PDO::PARAM_INT);
        $req->bindParam(':price', $price);
        $req->execute();

        $this->list_all();

    }

    function delete_destination($id_destination)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare('DELETE FROM destinations
                           WHERE id_destination = :id_destination;');

            $req->bindParam(':id_destination', $id_destination, PDO::PARAM_INT);
            $req->execute();
        }catch (PDOException $ex)
        {
            echo 'Deleting Exception:: '.$ex->getMessage();
        }
    }




}