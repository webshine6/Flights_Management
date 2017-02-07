<?php

require_once __DIR__.'/includes/functions.php';

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Category Management</title>
    </head>
<body>

     <div id="nav">

     </div>


     <div class="categories">

         <?php

            // Първоначално добавяме root директорията в навигацията [ELECTRONICS]
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
                    print_elements($path);
                }

                foreach ($subcategories_level_1 as $sub_category)
                {
                    $sub_category_id = $sub_category->category_id;

                    echo "<a href='./index.php?node=$sub_category_id'> $sub_category->name </a>";
                    echo "&nbsp; &nbsp; 
                    <a href='./includes/functions.php?delete&node=$sub_category_id'>[Delete]</a><br/>";
                }

            }

         ?>

     </div>

     <br/>
     Create new category: [<a href="create_category.php">Add</a>]

</body>
</html>
