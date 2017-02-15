<?php
require_once (__DIR__.'/../../includes/header.php');

$path = $_SERVER['SCRIPT_NAME'];

?>

    <h1>List All Reservations</h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Airline</th>
            <th>Destination</th>
            <th>Flight Date/Time</th>
            <th>Arrival Date/Time</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php


        if (isset($reservations))
        {
            foreach ($reservations as $reservation)
            {
                echo "
            <tr>

                <td>$reservation->airline</td>
                <td>$reservation->destination </td>
                <td>$reservation->flight_dt </td>
                <td>$reservation->arrival_dt </td>
                <td>
                   <a href=\"$path/reservations/delete/$reservation->id_reservation\" class=\"btn btn-primary\">
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

    <a href="<?php echo $_SERVER['SCRIPT_NAME'].'/reservations/add-reservation' ?>"
       class="btn btn-primary">
        Add Reservation
    </a>

<?php
require_once (__DIR__.'/../../includes/footer.php')
?>