<?php

require_once('./includes/functions.php');

$categories = get_categories();


// Обработка на заявка за добавяне на категория
if (isset($_POST['create']) && !empty($_POST))
{

   // pre_print($categories);

    $errors = array();

    $name = validate_input($_POST['name']);
    $parent_id = validate_input($_POST['parent_id']);

    /* --------------- Name Validation --------------------------------------*/
    if (empty($name))
    {
        $errors[] = '* Name is Required field.';
    }

    // It checks if all of the characters in the provided string are alphabetic.
    if (ctype_alpha($name) === FALSE)
    {
        $errors[] = '* Name must only contains letters and spaces.';
    }

    /* ------------ Parent Category (parent_id) Validation ------------------*/
    if (empty($parent_id))
    {
        $errors[] = '* Parent category is Required field.';
    }

    // array with category_id => name
    $cats_id_name = array();
    foreach ($categories as $category)
    {
       $cats_id_name[$category->category_id] = $category->name;
    }

    //pre_print($cats_id_name);

    // check if parent_id exists in $cats_id_name array
    if (!array_key_exists($parent_id, $cats_id_name))
    {
        $errors[] = '* Parent category not exists.';
    }

    // check if category name already exists in $cats_id_name array
    if (in_array(mb_strtoupper($name), $cats_id_name))
    {
        $errors[] = '* Category already exists.';
    }

   // pre_print($_POST);

    // check if no errors
    if (count($errors) == 0)
    {
       add_category(mb_strtoupper($name), $parent_id);
       header("Refresh: 0;");
    }

}


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


// Show add category form
require_once ('./form_add_category.html');

?>
