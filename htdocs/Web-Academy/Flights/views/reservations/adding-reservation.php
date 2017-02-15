
<?php
require_once (__DIR__.'/../../includes/header.php')
?>

<div class="row">
    <div class="col-md-4">
        <h1>Adding Reservation</h1>

        <form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'/reservations/create'; ?>">
            <div class="form-group">
                <label for="from">Airline: </label>
                <select class="form-control" id="from" name="id_airline" >
                    <?php
                    if (isset($airlines))
                    {
                        foreach ($airlines as $airline)
                        {
                            echo "<option value=\"$airline->id_airline\"> $airline->name</option>";

                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="to">Destination: </label>
                <select class="form-control" id="to" name="id_destination">
                    <?php
                    if (isset($destinations))
                    {
                        foreach ($destinations as $destination)
                        {
                            echo "<option value=\"$destination->id_destination\"> $destination->namefrom - $destination->nameto</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <div id="datetimepicker" class="input-append">
                        <label for="dt">Flight Date/Time:</label>
                        <input type="text" class="form-control" name="flight_dt"
                               value="<?php if (isset($_POST['flight_dt'])) {echo $_POST['flight_dt'];} ?>"
                               id="dt"/>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar" ></i>
                        </span>
                </div>

            </div>

            <div class="form-group">
                <div id="datetimepicker2" class="input-append">
                    <label for="dt">Arrival Date/Time:</label>
                    <input type="text" class="form-control" name="arrival_dt" id="dt"
                           value="<?php if (isset($_POST['arrival_dt'])) {echo $_POST['arrival_dt'];} ?>"/>
                    <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar" ></i>
                    </span>
                </div>

            </div>


            <!-- Datepicker here -->
            <span>DATETIME test: </span>
            <div class="form-group">
                <input type="text" id="ui"/>
            </div>



            <button type="submit" class="btn btn-primary" name="create">Submit</button>
        </form>

        <?php

        if(isset($errors))
        {
            foreach ($errors as $error)
            {
                echo "<p  class='alert alert-warning'> $error </p>";
            }
        }

        ?>

        <a href="./list-all">All Reservations</a>
    </div>
</div>

<?php
require_once (__DIR__.'/../../includes/footer.php')
?>
