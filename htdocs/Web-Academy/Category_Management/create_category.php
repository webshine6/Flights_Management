<?php

require_once('./includes/functions.php');

$categories = get_categories();

?>

  <!-- Форма за добавяне на категория -->
  <form method="post" action="includes/functions.php" >
       Name: <input type="text" name="name"> <br />

      <?php

        if (!empty($errors))
        {
            foreach ($errors as $error)
            {
                echo '<span class="error">$error</span>';
            }
        }

      ?>

      Parent Category:
      <select name="parent_id">
          <?php
            foreach ($categories as $category)
            {
                echo "<option value='$category->category_id'> $category->name </option>";
            }
          ?>
      </select> <br />

      <input type="submit" value="Create category" name="create">
  </form>