<?php

/**
 * Validate user input
 *
 * @param $data user input
 * @return string user input
 */
function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);

    return $data;

}
