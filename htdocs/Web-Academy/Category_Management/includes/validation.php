<?php

/**
 * Validate user input
 *
 * @param $data
 * @return string
 */
function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);

    return $data;
}
