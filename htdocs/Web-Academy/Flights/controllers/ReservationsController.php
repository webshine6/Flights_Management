<?php

require_once (__DIR__ . '/../models/Reservation.php');


class ReservationsController
{
    private $model;
    private $viewHelper;

    public function __construct()
    {
        $this->model = new Reservation();
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
                    case 'add-reservation':
                        $this->add_reservation();
                        break;

                    case 'create':
                        $this->create_reservation();
                        break;

                    case 'list-all':
                        $this->list_all_reservations();
                        break;

                    case 'delete':
                        if (isset($pathInfo[3]))
                        {
                            $this->delete_reservation($pathInfo[3]);
                        }
                        break;

                    case 'search':
                        $this->search_reservations();
                        break;

                    default:
                        $this->list_all_reservations();
                        break;

                }
            }else {
                $this->list_all_reservations();
            }

        }

    }


    function add_reservation()
    {
        $this->viewHelper->assign('title','Adding Reservation');
        $airlines = Reservation::get_airlines();

        $this->viewHelper->assign('airlines', $airlines);
        $destinations = Destination::list_all();;

        $this->viewHelper->assign('destinations',$destinations);

        $this->viewHelper->display('reservations','adding-reservation');

    }

    function create_reservation()
    {
        $result = $this->validate($_POST);

        if (!array_key_exists('success',$result))
        {
            $errors = $result;

            $this->viewHelper->assign('errors', $errors);
            $this->add_reservation();

        }else {
            Reservation::create_reservations($result['id_airline'],$result['id_destination'], $result['flight_dt'], $result['arrival_dt']);
            $this->viewHelper->assign('success', 'Reservation created successful');
            $this->list_all_reservations();
        }
    }

    function list_all_reservations()
    {
        $reservations = Reservation::get_reservations();

        $this->viewHelper->assign('reservations',$reservations);
        $this->viewHelper->assign('title','All Reservation');
        $this->viewHelper->display('reservations','list-all-reservations');
    }

    function delete_reservation($id)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_reservation 
                             FROM reservations
                             WHERE id_reservation = :id');

        $req->bindParam(':id',$id);
        $req->execute();

        $row_count = $req->rowCount();


        $errors = array();
        if ($row_count == 0)
        {
            $errors[] = '* Destination not exists';
        }else {
            Reservation::delete_reservations($id);
            $this->viewHelper->assign('success','Reservation deleted successful' );
        }


        $reservations = Reservation::get_reservations();

        $this->viewHelper->assign('errors',$errors);
        $this->viewHelper->assign('reservations', $reservations);

        $this->viewHelper->display('reservations','list-all-reservations');
    }


    function search_reservations()
    {
        // TODO
        //  $from;
        //  $to;
        //  $dt;

        // Reservation::search_reservation();
    }

    // TODO
    /**
     * Validate Flight
     * @param $data
     * @return array
     */
    private function validate($data)
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

        // flight_dt != arrival_dt
        if (!empty($data['flight_dt']) && !empty($data['arrival_dt']))
        {
            if ($data['flight_dt'] == $data['arrival_dt'])
            {
                $errors[] = 'Flight Date/Time must be different from Arrival Date/Time.';
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
                             FROM reservations
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