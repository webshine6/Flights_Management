<?php
    require_once (__DIR__.'/../../includes/header.php');
?>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
            <h1>Search Flights</h1>
            <form method="get" action="<?php echo $_SERVER['SCRIPT_NAME'].'/flights/search-for'; ?>">
                <div class="form-group">
                    <label for="from">Airport From: </label>
                    <select class="form-control" id="from" name="id_airport_from">
                        <?php
                        if (isset($airports))
                        {

                            foreach ($airports as $airport)
                            {
                                echo "<option value=\"$airport->id_airport\"> $airport->name</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="to">Airport To: </label>
                    <select class="form-control" id="to" name="id_airport_to">
                        <?php
                        if (isset($airports))
                        {
                            foreach ($airports as $airport)
                            {
                                echo "<option value=\"$airport->id_airport\"> $airport->name</option>";
                            }
                        }
                        ?>
                    </select>
                </div>



                <div class="form-group">
                    <label for="flight_dt">Flight Date/Time: </label>
                    <input type="text" name="flight_dt" class="form-control" id="flight_dt"/>

                    <script type="text/javascript">
                        jQuery('#flight_dt').datetimepicker();
                    </script>
                </div>

                <button type="submit" class="btn btn-primary" >Submit</button>
            </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Flight Date/Time</th>
                        <th>Price</th>
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
                                <td>$flight->namefrom - $flight->nameto </td>
                                <td>$flight->flight_dt </td>
                                <td>$flight->price </td>
                            </tr>";

                        }
                    }

                    ?>

                    </tbody>
                </table>

                <?php

                    if (isset($message))
                    {
                        echo "<p  class='alert alert-warning'> $message </p>";
                    }else {

                    }

                ?>

            </div>
        </div>

    </div>


<?php
require_once (__DIR__.'/../../includes/footer.php')
?>