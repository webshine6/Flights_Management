<?php
require_once (__DIR__.'/../../includes/header.php');

$path = $_SERVER['SCRIPT_NAME'];

?>

    <h1>All Destinations</h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Airport from</th>
            <th>Airport to</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php


        if (isset($destinations))
        {
            foreach ($destinations as $destination)
            {
                echo "
            <tr> 
               
                <td>$destination->namefrom </td>
                <td>$destination->nameto </td>
                <td>$destination->price </td>
                <td>
                   <a href=\"$path/destinations/delete/$destination->id_destination\" class=\"btn btn-primary\">
                   Delete</a>                                                  
                </td>     
                
            </tr>";

            }
        }


        if(isset($errors))
        {
            foreach ($errors as $error)
            {
                echo "<p  class='alert alert-warning'> $error </p>";
            }
        }

        if(isset($success))
        {
            echo "<p  class='alert alert-success'> $success </p>";
        }

        ?>

        </tbody>
    </table>

    <a href="<?php echo $_SERVER['SCRIPT_NAME'].'/destinations/add-destination' ?>"
        class="btn btn-primary">
        Add Destination
    </a>

<?php
require_once (__DIR__.'/../../includes/footer.php')
?>