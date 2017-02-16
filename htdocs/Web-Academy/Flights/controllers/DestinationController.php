<?php

require_once (__DIR__.'/../models/Destination.php');
require_once (__DIR__.'/../includes/validation.php');
require_once (__DIR__.'/../ViewHelper.php');

class DestinationController
{
    private $model;
    private $viewHelper;

    public function __construct()
    {
        $this->model = new Destination();
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
                    case 'add-destination':
                        $this->add_destination();
                        break;

                    case 'create':
                        $this->create_destination();
                        break;

                    case 'list-all':
                        $this->list_all_destinations();
                        break;

                    case 'delete':
                        if (isset($pathInfo[3]))
                        {
                            $this->delete_destination($pathInfo[3]);
                        }
                        break;

                    default:
                        $this->list_all_destinations();
                        break;
                }
            }else {
                $this->list_all_destinations();
            }
        }

    }

    function add_destination()
    {
        $airports = $this->model->get_airports();
        $this->viewHelper->assign('title','Adding Destination');
        $this->viewHelper->assign('airports', $airports);
        $this->viewHelper->display('destination','adding-destination');
    }

    function create_destination()
    {
        $result = $this->validate($_POST);

        if (!array_key_exists('success',$result))
        {
            $airports = Destination::get_airports();
            $errors = $result;

            $this->viewHelper->assign('errors', $errors);
            $this->viewHelper->assign('airports', $airports);
            $this->viewHelper->display('destination','adding-destination');
        }else {
            $this->model->create_destination($result['from'], $result['to'], $result['price']);

            $this->viewHelper->assign('success', 'Destination created successful');
            $this->list_all_destinations();
        }
    }

    function list_all_destinations()
    {
        $destinations = Destination::list_all();

        $this->viewHelper->assign('destinations',$destinations);
        $this->viewHelper->display('destination','list-all-destinations');
    }

    function delete_destination($id)
    {
        // check for existing destination
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_destination 
                             FROM destinations
                             WHERE id_destination = :id');

        $req->bindParam(':id',$id);
        $req->execute();

        $row_count = $req->rowCount();

        $errors = array();
        if ($row_count == 0)
        {
            $errors[] = '* Destination not exists';
        }

        // check for existing flight for deleting destination
        //---------------------------------------------------
        $db = Db::getInstance();
        $req = $db->prepare('
                    SELECT f.id_destination
                    FROM destinations dest, flights f
                    WHERE dest.id_destination = f.id_destination
                    AND dest.id_destination =  :id');

        $req->bindParam(':id',$id);
        $req->execute();

        $row_count = $req->rowCount();

        $errors = array();
        if ($row_count > 0) {
            $errors[] = '* Deleting Filed. There is a flight to this destination';
        }

        // ---------------------------------------------------

        if (empty($errors))
        {
            $this->model->delete_destination($id);
            $this->viewHelper->assign('success','Destination deleted successful' );
        }


        $this->viewHelper->assign('errors',$errors);
        $this->list_all_destinations();

    }


    private function validate($data)
    {

        $rules = array(
            'from'  => '* Airport From is Required field.',
            'to'    => '* Airport To is Required field.',
            'price' => '* Price is Required field'
        );

        $errors = array();
        $output   = array();

        // check for required fields
        foreach ($data as $key=>$value)
        {
            if (empty($value) && array_key_exists($key, $rules))
            {
                $errors[] = $rules[$key];
            }

        }

        // check for numeric value
        if (isset($data['price']))
        {
            if (!is_numeric((float)$data['price']))
            {
                $errors[] = '* Price must be numeric';
            }

        }

        // from != to
        if ($data['from'] == $data['to'])
        {
            $errors[] = 'Airport from must be different from airport to.';
        }


        // check airport from for exists
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_airport FROM airports WHERE id_airport = :from');
        $req->bindParam(':from', $data['from']);
        $req->execute();

        $row_count = $req->rowCount();

        if ($row_count == 0)
        {
            $errors[] = '* Airport From not exists';
        }

        // check airport to for exists
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_airport
                             FROM airports 
                             WHERE id_airport = :to');
        $req->bindParam(':to', $data['to']);
        $req->execute();

        $row_count = $req->rowCount();
        if ($row_count == 0)
        {
            $errors[] = '* Airport To not exists';
        }

        // check airport from-to pair for exists
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id_airport_from, id_airport_to
                             FROM destinations
                             WHERE id_airport_from = :from
                             AND   id_airport_to = :to');
        $req->bindParam(':from', $data['from']);
        $req->bindParam(':to', $data['to']);
        $req->execute();

        $row_count = $req->rowCount();
        if ($row_count > 0)
        {
            $errors[] = '* Airport already exists';
        }


        if (is_array($errors) && !empty($errors))
        {
            return $errors;
        }else {
            $output['success'] = 'success';
            $output['from']    = $data['from'];
            $output['to']      = $data['to'];
            $output['price']   = $data['price'];
            return $output;
        }
    }

}


