<?php
require_once (__DIR__.'/../../includes/header.php');

$path = $_SERVER['SCRIPT_NAME'];

?>

    <h1>List All Flights</h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Airline</th>
            <th>Destination</th>
            <th>Flight Date/Time</th>
            <th>Arrival Date/Time</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php


        if (isset($flights))
        {
            foreach ($flights as $flight)
            {
                echo "
            <tr>

                <td>$flight->airline</td>
                <td>$flight->destination </td>
                <td>$flight->flight_dt </td>
                <td>$flight->arrival_dt </td>
                <td>$flight->price </td>
                <td>
                   <a href=\"$path/flights/delete/$flight->id_reservation\" class=\"btn btn-primary\">
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

        ?>

        </tbody>
    </table>

<?php


if(isset($success))
{
    echo "<p  class='alert alert-success'> $success </p>";
}

?>

    <a href="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/add-flight' ?>"
       class="btn btn-primary">
        Add Flight
    </a>

<?php
require_once (__DIR__.'/../../includes/footer.php')
?>