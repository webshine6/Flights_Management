<?php

    require_once (__DIR__.'/../../includes/header.php');

?>

    <div class="row">
        <div class="col-md-6">
            <h1>Adding Flight</h1>

            <form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/create'; ?>">
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
                    <label for="from">Flight Date/Time: </label>
                    <input type="text" name="flight_dt" class="form-control" id="flight_dt"/>

                    <script type="text/javascript">
                        jQuery('#flight_dt').datetimepicker();
                    </script>
                </div>

                <div class="form-group">
                    <label for="from">Arrival Date/Time: </label>
                    <input type="text" name="arrival_dt" class="form-control" id="arrival_dt"/>

                    <script type="text/javascript">
                        jQuery('#arrival_dt').datetimepicker();
                    </script>
                </div>


                <button type="submit" class="btn btn-primary" >Submit</button>
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

            <a href="./list-all">All Flights</a>
        </div>
</div>

<?php
require_once (__DIR__.'/../../includes/footer.php')
?>
