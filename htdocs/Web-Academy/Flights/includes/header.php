<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if (isset($title)) {echo $title;} else {echo 'Home';};?></title>

    <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/ui-lightness/jquery-ui.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
    <style>
        .logo{
            text-align:center;
            margin:200px auto;
        }
        .logo img{
            width:350px;
        }


        .logo p {
            color:#272727;
            font-size:40px;
            margin-top:1px;
        }

    </style>
</head>
<body>
<div class="container">

<nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Flight Management</a>
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo $_SERVER['SCRIPT_NAME'].'/airlines/' ?>">
                        Airlines</a>
                </li>
                <li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'/destinations/' ?>">
                        Destinations</a>
                </li>

                <li class="dropdown active">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/' ?>">
                        Flights
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/list-all/' ?>">All Flights</a></li>
                        <li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/add-flight/' ?>">Add Flight</a></li>
                        <li><a href="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/search-form/' ?>">Search Flight</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
