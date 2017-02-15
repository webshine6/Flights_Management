<?php
    require_once (__DIR__.'/../../includes/header.php')
?>

    <div class="row">
        <div class="col-md-4">
            <h1>Adding Airline</h1>

            <form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'].'/airlines/create'; ?>">
                <div class="form-group">
                    <label for="airline">Airline:</label>
                    <input type="text" class="form-control" name="airline" id="airline">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>


            <?php

                if(isset($errors))
                {
                    foreach ($errors as $error)
                    {
                        echo "<p  class='alert alert-warning'> $error </p>";
                    }
                }

                if (isset($success))
                {
                    echo "<p  class='alert alert-success'> $success </p>";
                }


            ?>

            <a href="./list-all">All Airlines</a>

        </div>
    </div>

<?php
    require_once (__DIR__.'/../../includes/footer.php')
?>


