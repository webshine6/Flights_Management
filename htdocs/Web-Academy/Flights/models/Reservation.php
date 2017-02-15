<?php

require_once(__DIR__.'./db_connection.php');

class Reservation
{

    public static function get_airlines()
    {
        $db = Db::getInstance();
        $req = $db->query('SELECT id_airline, name
                           FROM airlines
                           ORDER BY name');
        $airlines = $req->fetchAll();
        return $airlines;
    }

    public static function get_reservations()
    {
        $db = Db::getInstance();
        $req = $db->query('
                select res.id_reservation, res.id_airline, a.name as airline, a1.name as airportfrom, a2.name as airporto,
               concat(a1.name, " - ", a2.name) as destination,
               res.flight_dt, res.arrival_dt
               from reservations as res
            
              join airlines as a
              on res.id_airline = a.id_airline
            
              join destinations as dest
              on res.id_destination = dest.id_destination
              
              join airports as a1
              on dest.id_airport_from = a1.id_airport
              
             join airports as a2
             on dest.id_airport_to = a2.id_airport');
        $reservations = $req->fetchAll();

        return $reservations;
    }

    public static function create_reservations($id_airline, $id_destination, $flight_dt, $arrival_dt)
    {
        $db = Db::getInstance();

        $req = $db->prepare("
            INSERT INTO reservations (id_airline, id_destination, flight_dt, arrival_dt)
            VALUES (:id_airline, :id_destination, :flight_dt, :arrival_dt)");

        $req->bindParam(':id_airline', $id_airline, PDO::PARAM_INT);
        $req->bindParam(':id_destination', $id_destination, PDO::PARAM_INT);
        $req->bindParam(':flight_dt', $flight_dt);
        $req->bindParam('arrival_dt', $arrival_dt);
        $req->execute();

        Reservation::get_reservations();
    }

    public static function delete_reservations($id_reservation)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare('DELETE FROM reservations
                           WHERE id_reservation = :id_reservation;');

            $req->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
            $req->execute();
        }catch (PDOException $ex)
        {
            echo 'Deleting Exception:: '.$ex->getMessage();
        }
    }


    public static function search_reservation($from, $to, $dt)
    {
        $db = Db::getInstance();
        $req = $db->prepare('
                SELECT *
                FROM destinations as dest, reservations as res
                WHERE dest.id_airport_from = :from
                AND dest.id_airport_to = :to
                AND res.flight_dt = :dt');

        $req->bindParam(':from', $from);
        $req->bindParam(':to', $to);
        $req->bindParam(':dt', $dt);

        $reservations = $req->fetchAll();
        return $reservations;
    }

}