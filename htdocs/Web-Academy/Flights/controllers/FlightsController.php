<?php

require_once (__DIR__ . '/../models/Flight.php');
require_once (__DIR__ . '/../models/Destination.php');

class FlightsController
{
    private $model;
    private $viewHelper;

    public function __construct()
    {
        $this->model = new Flight();
        $this->viewHelper = new ViewHelper();
    }


    public function call()
    {

        if (isset($_SERVER['PATH_INFO']))
        {
            $pathInfo = explode('/',$_SERVER['PATH_INFO']);

            if (isset($pathInfo[2]))
            {
                switch ($pathInfo[2])
                {
                    case 'add-flight':
                        $this->add_flight();
                        break;

                    case 'create':
                        $this->create_flight();
                        break;

                    case 'list-all':
                        $this->list_all_flights();
                        break;

                    case 'delete':
                        if (isset($pathInfo[3]))
                        {
                            $this->delete_flight($pathInfo[3]);
                        }
                        break;

                    case 'search-form':
                        $this->show_search_flights_form();
                        break;

                    case 'search-for':
                        $this->search_flight();
                        break;

                    default:
                        $this->list_all_flights();
                        break;

                }
            }else {
                $this->list_all_flights();
            }

        }

    }


    function add_flight()
    {
        $this->viewHelper->assign('title','Adding Flight');

        $airlines = Flight::get_airlines();
        $this->viewHelper->assign('airlines', $airlines);

        $destinations = Destination::list_all();;
        $this->viewHelper->assign('destinations',$destinations);

        $this->viewHelper->display('flights','adding-flight');

    }

    function create_flight()
    {
        $result = $this->validate_add_flight($_POST);

        if (!array_key_exists('success',$result))
        {
            $errors = $result;

            $this->viewHelper->assign('errors', $errors);
            $this->add_flight();

        }else {
            Flight::create_flight($result['id_airline'],$result['id_destination'], $result['flight_dt'], $result['arrival_dt']);
            $this->viewHelper->assign('success', 'Flight created successful');
            $this->list_all_flights();
        }
    }

    function list_all_flights()
    {
        $flights = Flight::get_flights();

        $this->viewHelper->assign('flights',$flights);
        $this->viewHelper->assign('title','All Flights');
        $this->viewHelper->display('flights','list-all-flights');
    }

    function delete_flight($id)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_reservation 
                             FROM flights
                             WHERE id_reservation = :id');

        $req->bindParam(':id',$id);
        $req->execute();

        $row_count = $req->rowCount();


        $errors = array();
        if ($row_count == 0)
        {
            $errors[] = '* Flight not exists';
        }else {
            Flight::delete_flight($id);
            $this->viewHelper->assign('success','Flight deleted successful' );
        }


        $reservations = Flight::get_flights();

        $this->viewHelper->assign('errors',$errors);
        $this->viewHelper->assign('flights', $reservations);

        $this->viewHelper->display('flights','list-all-flights');
    }

    function show_search_flights_form()
    {
        $airlines = Flight::get_airlines();
        $airports = Flight::get_airports();

        $this->viewHelper->assign('airlines', $airlines);
        $this->viewHelper->assign('airports', $airports);
        $this->viewHelper->assign('title', 'Search Flights');
        $this->viewHelper->display('flights','search-flight');

    }


    function search_flight()
    {
        $from = validate_input($_GET['id_airport_from']);
        $to   = validate_input($_GET['id_airport_to']);
        $dt   = validate_input($_GET['flight_dt']);

        $airports = Flight::get_airports();

        $flights = Flight::search_flight($from, $to, $dt);
        if (empty($flights))
        {
            $this->viewHelper->assign('message','Sorry, No Flights');
        }
        $this->viewHelper->assign('airports', $airports);
        $this->viewHelper->assign('flights',$flights);
        $this->viewHelper->assign('title','Search Reservation');
        $this->viewHelper->display('flights','search-flight');

    }

    /**
     * Validate Create New Flight
     * @param $data
     * @return array
     */
    private function validate_add_flight($data)
    {
        $rules = array(
            'id_airline'        => '* Airline is Required field.',
            'id_destination'    => '* Destination is Required field.',
            'flight_dt'         => '* Flight Date/Time is Required field',
            'arrival_dt'        => '* Arrival Date/Time is Required field'
        );

        $errors = array();
        $output   = array();

        foreach ($data as $key=>$value)
        {
            if (empty($value) && array_key_exists($key, $rules))
            {
                $errors[] = $rules[$key];
            }

        }

        /**
         * flight_dt != arrival_dt and arrival_dt > flight_dt
         */
        if (!empty($data['flight_dt']) && !empty($data['arrival_dt']))
        {
            if ($data['flight_dt'] == $data['arrival_dt'])
            {
                $errors[] = 'Flight Date/Time must be different from Arrival Date/Time.';
            }

            if ($data['flight_dt'] > $data['arrival_dt'])
            {
                $errors[] = 'Arrival Date/Time must be after Flight Date/Time.';
            }

        }


        // check airline for exists
        if (!empty($data['id_airline']))
        {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT id_airline FROM airlines WHERE id_airline = :id_airline');
            $req->bindParam(':id_airline', $data['id_airline']);
            $req->execute();

            $row_count = $req->rowCount();

            if ($row_count == 0)
            {
                $errors[] = '* Airline Not exists';
            }

        }

        // check destination for exists
        if (!empty($data['id_destination']))
        {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT id_destination
                             FROM destinations 
                             WHERE id_destination = :id_destination');
            $req->bindParam(':id_destination', $data['id_destination']);
            $req->execute();

            $row_count = $req->rowCount();

            if ($row_count == 0)
            {
                $errors[] = '* Destination Not exists';
            }

        }

        if (empty($errors))
        {
            // check flight for exists
            $db = Db::getInstance();
            $req = $db->prepare('SELECT id_reservation, id_airline, id_destination,
                             flight_dt, arrival_dt
                             FROM flights
                             WHERE id_airline = :id_airline
                             AND   id_destination = :id_destination
                             AND   flight_dt = :flight_dt
                             AND   arrival_dt = :arrival_dt');

            $req->bindParam(':id_airline', $data['id_airline']);
            $req->bindParam(':id_destination', $data['id_destination']);
            $req->bindParam(':flight_dt', $data['flight_dt']);
            $req->bindParam(':arrival_dt', $data['arrival_dt']);

            $req->execute();

            $row_count = $req->rowCount();

            if ($row_count > 0)
            {
                $errors[] = '* Flight already exists';
            }
        }


        if (is_array($errors) && !empty($errors))
        {
            return $errors;
        }else {
            $output['success']        = 'success';
            $output['id_airline']     = $data['id_airline'];
            $output['id_destination'] = $data['id_destination'];
            $output['flight_dt']      = $data['flight_dt'];
            $output['arrival_dt']     = $data['arrival_dt'];
            return $output;
        }
    }

}