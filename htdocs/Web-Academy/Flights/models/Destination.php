<?php

class Destination
{
    /**
     * Get All Airports to display in create destination form
     * @return array|string airports
     */
    public static function get_airports()
    {
        $airports = '';
        try {
            $db = Db::getInstance();
            $req = $db->query('SELECT id_airport, name
                           FROM airports
                           ORDER BY name');
            $airports = $req->fetchAll();
        }catch (PDOException $e) {
            echo 'Get airports Exception. '.$e->getMessage();
        }

        return $airports;
    }

    /**
     * Get all destinations
     *
     * @return array|string destinations
     */
    public static function list_all()
    {
        $destinations = '';
        try {
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
        }catch (PDOException $e) {
            echo 'Get All destinations Exception. '.$e->getMessage();
        }

        return $destinations;
    }

    /**
     * Create new Destination
     *
     * @param $id_airport_from int aiport from
     * @param $id_airport_to int airport to
     * @param $price double destination price
     */
    public static function create_destination($id_airport_from, $id_airport_to, $price)
    {
       try {
           $db = Db::getInstance();

           $req = $db->prepare("
                INSERT INTO destinations (id_airport_from, id_airport_to, price)
                VALUES (:id_airport_from, :id_airport_to, :price)");

           $req->bindParam(':id_airport_from', $id_airport_from, PDO::PARAM_INT);
           $req->bindParam(':id_airport_to', $id_airport_to, PDO::PARAM_INT);
           $req->bindParam(':price', $price);
           $req->execute();
       }catch (PDOException $e) {
           echo 'Create destinations Exception. '.$e->getMessage();
       }

    }

    /**
     * Delete destination by id_destination
     *
     * @param $id_destination int destination id to delete
     */
    public static function delete_destination($id_destination)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare('
                           DELETE FROM destinations
                           WHERE id_destination = :id_destination;');

            $req->bindParam(':id_destination', $id_destination, PDO::PARAM_INT);
            $req->execute();
        }catch (PDOException $ex) {
            echo 'Deleting Destination Exception:: '.$ex->getMessage();
        }
    }

}