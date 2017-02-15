<?php

require_once('./models/db_connection.php');
require_once('./controllers/ReservationsController.php');
require_once('./controllers/AirlinesController.php');
require_once ('./controllers/DestinationController.php');

function pre_print($var) {
    echo '<pre>';
    echo print_r($var, true);
    echo '</pre>';
}

/**
 *
 * the single point of entry for all requests
 */
// GET Requests
if (isset($_SERVER['PATH_INFO']))
{
    // list-all-reservations.php/getFlights/1
    $pathInfo = explode('/',$_SERVER['PATH_INFO']);


    switch ($pathInfo[1])
    {

        case 'destinations': // $pathInfo[1] = destinations
            $controller = new DestinationController();
            $controller->call();
            break;

        case 'airlines': // $pathInfo[1] = airlines
            $controller = new AirlinesController();
            $controller->call();
            break;

        case 'reservations': // $pathInfo[1] = reservations
            $controller = new ReservationsController();
            $controller->call();
            break;


        default:
            require_once ('./views/home.php');

    }

}




