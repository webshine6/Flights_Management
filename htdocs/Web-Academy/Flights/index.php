<?php

require_once('./models/db_connection.php');
require_once('./controllers/FlightsController.php');
require_once('./controllers/DestinationController.php');
require_once('./controllers/AirlinesController.php');
require_once (__DIR__.'/includes/header.php');

?>

<!--    <nav class="navbar navbar-inverse">-->
<!--        <div class="container-fluid">-->
<!--            <div class="navbar-header">-->
<!--                <a class="navbar-brand" href="#">Flight Management</a>-->
<!--            </div>-->
<!--            <ul class="nav navbar-nav">-->
<!--                <li>-->
<!--                    <a href="--><?php //echo $_SERVER['SCRIPT_NAME'].'/airlines/' ?><!--">-->
<!--                        Airlines</a>-->
<!--                </li>-->
<!--                <li><a href="--><?php //echo $_SERVER['SCRIPT_NAME'].'/destinations/' ?><!--">-->
<!--                        Destinations</a>-->
<!--                </li>-->
<!---->
<!--                <li class="dropdown active">-->
<!--                    <a class="dropdown-toggle" data-toggle="dropdown" href="--><?php //echo $_SERVER['SCRIPT_NAME'].'/flights/' ?><!--">-->
<!--                        Flights-->
<!--                        <span class="caret"></span></a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        <li><a href="--><?php //echo $_SERVER['SCRIPT_NAME'].'/flights/add-flight/' ?><!--">Add Flight</a></li>-->
<!--                        <li><a href="--><?php //echo $_SERVER['SCRIPT_NAME'].'/flights/search-form/' ?><!--">Search Flight</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </nav>-->

<?php

/**
 *
 * The single point of entry for all requests
 */
if (isset($_SERVER['PATH_INFO']))
{
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

        case 'flights': // $pathInfo[1] = flights
            $controller = new FlightsController();
            $controller->call();
            break;

        default:
            require_once ('error-page.php');
    }

}

require_once (__DIR__.'/includes/footer.php');

?>