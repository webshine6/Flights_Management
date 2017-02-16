<?php

require_once(__DIR__.'./db_connection.php');

class Flight
{

    public static function get_airlines()
    {
        $airlines = '';

        try {
            $db = Db::getInstance();
            $req = $db->query('
                           SELECT id_airline, name
                           FROM airlines
                           ORDER BY name');
            $airlines = $req->fetchAll();
        }catch (PDOException $e) {
            echo 'Get airlines Exception. '.$e->getMessage();
        }

        return $airlines;
    }

    public static function get_airports()
    {
        $airports = '';
        try {
            $db = Db::getInstance();
            $req = $db->query('
                           SELECT id_airport, name
                           FROM airports
                           ORDER BY name');
            $airports = $req->fetchAll();
        }catch (PDOException $e) {
            echo 'Get Airports Exception.'. $e->getMessage();
        }
        return $airports;
    }

    public static function get_flights()
    {
        $db = Db::getInstance();
        $req = $db->query('
                select res.id_reservation, res.id_airline, a.name as airline, a1.name as airportfrom, a2.name as airporto,
               concat(a1.name, " - ", a2.name) as destination,
               res.flight_dt, res.arrival_dt, dest.price
               from flights as res
            
              join airlines as a
              on res.id_airline = a.id_airline
            
              join destinations as dest
              on res.id_destination = dest.id_destination
              
              join airports as a1
              on dest.id_airport_from = a1.id_airport
              
             join airports as a2
             on dest.id_airport_to = a2.id_airport');
        $flights = $req->fetchAll();

        return $flights;
    }

    public static function create_flight($id_airline, $id_destination, $flight_dt, $arrival_dt)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare("
                    INSERT INTO flights (id_airline, id_destination, flight_dt, arrival_dt)
                    VALUES (:id_airline, :id_destination, :flight_dt, :arrival_dt)");

            $req->bindParam(':id_airline', $id_airline, PDO::PARAM_INT);
            $req->bindParam(':id_destination', $id_destination, PDO::PARAM_INT);
            $req->bindParam(':flight_dt', $flight_dt);
            $req->bindParam(':arrival_dt', $arrival_dt);
            $req->execute();
        }catch (PDOException $e) {
            echo 'Create Flight Exception. '.$e->getMessage();
        }
    }

    public static function delete_flight($id_flight)
    {
        try {
            $db = Db::getInstance();

            $req = $db->prepare('
                        DELETE FROM flights
                        WHERE id_reservation = :id_reservation;');

            $req->bindParam(':id_reservation', $id_flight, PDO::PARAM_INT);
            $req->execute();
        }catch (PDOException $ex) {
            echo 'Deleting Flight Exception:: '.$ex->getMessage();
        }
    }


    public static function search_flight($from, $to, $dt)
    {
        $flights = '';
        try {
            $db = Db::getInstance();
            $req = $db->prepare('
                       SELECT dest1.id_airport_from, a1.name as namefrom,
                       dest1.id_airport_to, a2.name as nameto, dest1.price, res.flight_dt
                       FROM destinations AS dest1
                       JOIN airports AS a1
                       ON dest1.id_airport_from = a1.id_airport
                       JOIN airports AS a2
                       ON dest1.id_airport_to = a2.id_airport
                       JOIN flights as res
                       ON dest1.id_destination = res.id_destination
                    
                       WHERE dest1.id_airport_from = :from
                       AND dest1.id_airport_to = :to
                       AND res.flight_dt = :dt;');

            $req->bindParam(':from', $from, PDO::PARAM_INT);
            $req->bindParam(':to', $to,  PDO::PARAM_INT);
            $req->bindParam(':dt', $dt);
            $req->execute();

            $flights = $req->fetchAll();
        }catch (PDOException $e) {
            echo 'Searching Flights Exception: '.$e->getMessage();
        }

        return $flights;
    }

}