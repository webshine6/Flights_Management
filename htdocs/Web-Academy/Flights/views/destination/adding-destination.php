<?php
    require_once (__DIR__.'/../../includes/header.php')
?>

<div class="row">
    <div class="col-md-4">
        <h1>Adding Destination</h1>

        <form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'/destinations/create'; ?>">
            <div class="form-group">
                <label for="from">From Airport: </label>
                <select class="form-control" id="from" name="from">
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
                <label for="to">To Airport: </label>
                <select class="form-control" id="to" name="to">
                    <?php
                    foreach ($airports as $airport)
                    {
                        echo "<option value=\"$airport->id_airport\"> $airport->name</option>";
                    }

                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price">
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

        <a href="./list-all">All Destinations</a>
    </div>
</div>

<?php
    require_once (__DIR__.'/../../includes/footer.php')
?>
