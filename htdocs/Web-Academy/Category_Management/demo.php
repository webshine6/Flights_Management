<?php


if (isset($_GET['name']))
{
    $name = $_GET['name'];

    $name = trim($name);
    $name = stripslashes($name);
    $name = htmlspecialchars($name);
    $name = htmlentities($name);

    echo $name;
}

