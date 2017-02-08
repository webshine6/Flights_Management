<?php

require_once __DIR__.'/../classes/PDOConnection.php';
require_once __DIR__.'./validation.php';

function pre_print($var) {
    echo '<pre>';
    echo print_r($var, true);
    echo '</pre>';
}

// Принтиране на елементите в навигацията
function print_elements($categories, $node)
{
    foreach ($categories as $category)
    {
        if (is_array($category))
        {
            print_elements($category, $node);
        }else {
            echo "[$category->name] > ";
        }
    }
    echo "<a href='?delete&node=$node'>[Delete]</a>";
    echo "<br/>";
}

// вземаме parent категорията за добавяне на root директорията в навигацията
function get_parent_directory($node)
{
    // Получаваме връзката към БД
    $conn = PDOConnection::getInstance()->getConnection();

    try {
        $statement = $conn->query("
                   SELECT cat1.*
                   FROM category cat1, category cat2
	               WHERE cat1.category_id = cat2.parent_id
                   AND cat2.category_id =$node");

        $statement->execute();
        $result = $statement->fetchObject();

    }catch (PDOException $e) {
        echo 'Get Parent Category Exception: '.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }

    return $result;
}

// вземаме само едно ниво поддиректории надолу
function get_subcategories_level_1($node)
{
    $array = array();
    try {
        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $statement = $conn->prepare("
                     SELECT cat2.category_id, cat2.name, cat2.parent_id 
                     FROM category cat1, category cat2
                     WHERE cat1.category_id = cat2.parent_id
                     AND cat2.parent_id = :node;");

        $statement->bindParam(':node', $node);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $value)
        {
            $array[] = $value;
        }

    }catch (PDOException $e) {
        echo 'Get Subcategories ERROR: '.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }

    return $array;
}

// parent_id = задаваме подкатегория на коя категория ще добавяме
function add_category($name, $parent_id)
{
    try {

        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $statement = $conn->prepare("
            INSERT INTO category (name, parent_id)
            VALUES (:name, :parent_id)");

        $statement->bindParam(':name', $name);
        $statement->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);

        $statement->execute();

    }catch (PDOException $e) {
        echo 'Adding category ERROR: '.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }

}

// вземаме всички категории
function get_categories()
{
    try {
        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $statement = $conn->prepare('SELECT category_id, name FROM category');
        $statement->execute();
        $result = $statement->fetchAll();
    }catch (PDOException $e) {
        echo 'Selecting category ERROR: '.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }

    return $result;
}

// вземаме целия път до категорията нагоре
function get_path($node)
{
    $array = array();
    try {
        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $statement = $conn->prepare("
                    SELECT cat1.category_id, cat1.name, cat1.parent_id 
                    FROM category cat1
	                WHERE  cat1.category_id = :node;
       ");

       $statement->bindParam(':node', $node, PDO::PARAM_INT);
       $statement->execute();
       $result = $statement->fetchAll();

       if (!empty($result))
       {
           foreach ($result as $value)
           {
               $array[] =  $value;
               $v =  $value->parent_id;

               if ($v != null)
               {
                   $res = get_path($v);

                   if(!empty($res))
                   {
                       $array = array_merge($res,$array);
                   }

               }

           }
       }
   }catch (PDOException $e) {
       echo 'Get Path Exception: '.$e->getMessage();
       die();
   }finally {
       $statement = null;
       $conn = null;
   }

   return $array;
}

// вземаме всички подкатегории на дадена категория [[ 2 ]]
function get_subcategories($node)
{
    $array = array();

    try {
        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $statement = $conn->prepare("
            SELECT cat2.* 
                FROM category cat1, category cat2
            WHERE 
                cat1.category_id = cat2.parent_id
            AND cat2.parent_id = :node;");

        $statement->bindParam(':node', $node);
        $statement->execute();
        $result = $statement->fetchAll();

        if (!empty($result))
        {
            foreach ($result as $value)
            {
                $array[] = $value;

                $category_id = $value->category_id;

                $sub_categories = get_subcategories($category_id);

                if (!empty($sub_categories))
                {
                    $array = array_merge($sub_categories, $array);
                }
            }

        }
    }catch (PDOException $e) {
        echo 'PDO Exception ERROR: '.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }

    return $array;
}

// изтриване на категория и всички подкатегории
function delete_children_directories($node)
{
    try {
        // Получаваме връзката към БД
        $conn = PDOConnection::getInstance()->getConnection();

        $children_directories = get_subcategories($node);

        if (!empty($children_directories))
        {
            foreach ($children_directories as $children_directory)
            {
                delete_children_directories($children_directory->category_id);

                //echo "<p> Изтриваме директория:: $children_directory->name </p>";
                if ($children_directory->parent_id === $node)
                {
                    //echo "<h3> <i> Сега трябва да изтрием и parent директорията с id = $node </i> </h3>";
                    delete_children_directories($children_directory->category_id);

                    $statement = $conn->prepare("
                             DELETE cat1.*
                             FROM category cat1
                             WHERE cat1.category_id = :node;");

                    $statement->bindParam(':node', $node);
                    $statement->execute();
                }

            }

        }

        $statement = $conn->prepare("
                 DELETE cat1.*
                 FROM category cat1
                 WHERE cat1.category_id = :node;");

        $statement->bindParam(':node', $node);
        $statement->execute();

    }catch (PDOException $e) {
        echo 'Deleting Categories Exception'.$e->getMessage();
        die();
    }finally {
        $statement = null;
        $conn = null;
    }
}

function cats_id_name()
{
    $categories = get_categories();

    $cats_id_name = array();
    foreach ($categories as $category)
    {
        $cats_id_name[$category->category_id] = $category->name;
    }

    return $cats_id_name;
}

