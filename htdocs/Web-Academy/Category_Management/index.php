<?php

require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/header.html';

require_once __DIR__.'/create_category.php';



            // Първоначално добавяме root директорията в навигацията [Home]
            $root = get_parent_directory(2);
            $root_id = $root->category_id;
            echo "<a href='./index.php?node=$root_id'> $root->name </a>";

            if (isset($_GET['node']) && !empty($_GET))
            {
                $node = addslashes(htmlentities(htmlspecialchars($_GET['node'])));

                echo "<hr/>";
                $subcategories_level_1 = get_subcategories_level_1($node);

                if ($node != 1)
                {
                    $path = get_path($node);
                    print_elements($path, $node);
                }

                foreach ($subcategories_level_1 as $sub_category)
                {
                    $sub_category_id = $sub_category->category_id;

                    echo "<a href='./index.php?node=$sub_category_id'> $sub_category->name </a>";
                    echo "&nbsp; &nbsp; 
                    <a href='?delete&node=$sub_category_id'>[Delete]</a><br/>";
                }

                echo "<br/> Create new category: [<a href='?create&node=$node'>Add]</a><br/><br/>";
            }


if (isset($_GET['create']))
{
   // Show add category form
   require_once ('./form_add_category.html');
}

// Обработка на заявка за изтриване на категория
if (isset($_GET['delete']))
{
    $node = validate_input($_GET['node']);

    $errors = array();

    if (empty($node))
    {
        $errors[] = '* Category id is Required.';
    }

    $cats_id_name = cats_id_name();
    //pre_print($cats_id_name);

    // check if $node exists in $cats_id_name array
    if (!array_key_exists($node, $cats_id_name))
    {
        $errors[] = '* Category id not exists.';
    }

    // check $node is Home directory
    if ($node == 1)
    {
        $errors[] = '* Can\'t delete Home category.';
    }


    if (count($errors) == 0)
    {
        delete_children_directories($node);
        header("Location: index.php");
    }

}

if (isset($errors))
{
    foreach ($errors as $error)
    {
        echo "<p class='error'> $error </p>";
    }
}


require_once __DIR__.'/includes/footer.html';