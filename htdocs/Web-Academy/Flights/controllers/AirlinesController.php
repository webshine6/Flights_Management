<?php

require_once(__DIR__.'/../models/db_connection.php');
require_once (__DIR__.'/../models/Airline.php');
require_once (__DIR__.'/../includes/validation.php');
require_once (__DIR__.'/../ViewHelper.php');


class AirlinesController
{
    private $model;
    private $viewHelper;

    public function __construct()
    {
        $this->model = new Airline();
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
                    case 'add-airline':
                        $this->add_airline();
                        break;

                    case 'create':
                        $this->create_airline();
                        break;

                    case 'list-all':
                        $this->list_all_airlines();
                        break;

                    default:
                        $this->list_all_airlines();
                        break;

                }
            }else {
                $this->list_all_airlines();
            }

        }

    }

    public function add_airline()
    {
        $this->viewHelper->assign('title','Adding Airline');
        $this->viewHelper->display('airlines', 'adding-airline');
    }

    public function create_airline()
    {
        $data = array();

        foreach ($_POST as $key=>$value)
        {
            $data[$key] =  validate_input($value);
        }

        $result = $this->validate($data);

        if (is_array($result) && count($result) > 0)
        {
            $errors = $result;
            $this->viewHelper->assign('errors',$errors);
            $this->viewHelper->display('airlines','adding-airline');
        }else {
            Airline::create_airline($result);
            $this->viewHelper->assign('success', 'Destination created successful');
            $this->list_all_airlines();

        }
    }

    public function list_all_airlines()
    {
       $airlines = $this->model->list_all();
       $this->viewHelper->assign('title','All Airlines');
       $this->viewHelper->assign('airlines',$airlines);
       $this->viewHelper->display('airlines','list-all-airlines');
    }

    private function validate($data)
    {
        $rules = array(
            'airline'  => '*  Name is Required field.',
        );

        $errors = array();

        foreach ($data as $key=>$value)
        {
            if (empty($value) && array_key_exists($key, $rules))
            {
                $errors[] = $rules[$key];
            }

            // checks if all of the characters in the provided string are alphabetic.
            if (ctype_alpha(str_replace(array("\n", "\t", ' '), '', $value)) === FALSE)
            {
                $errors[] = '* Name must only contains letters and spaces.';
            }

            if (mb_strlen($value)< 3)
            {
                $errors[] = '* Name is too short.';
            }

        }

        // check airline for exists
        $db = Db::getInstance();
        $req = $db->prepare('SELECT name FROM airlines WHERE name = :name');
        $req->bindParam(':name', $data['airline']);
        $req->execute();

        $row_count = $req->rowCount();

        if ($row_count > 0)
        {
            $errors[] = '* Airline already exists';
        }

        if (is_array($errors) && !empty($errors))
        {
            return $errors;
        }else {
            return $data['airline'];
        }

    }

}