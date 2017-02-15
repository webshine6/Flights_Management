<?php
    require_once (__DIR__.'/../../includes/header.php')
?>

   <div class="row">
       <div class="col-md-6 col-md-offset-3" >
           <h1>All Airlines</h1>

           <table class="table table-hover">
               <thead>
               <tr>
                   <th>#ID</th>
                   <th>Name</th>
               </tr>
               </thead>
               <tbody>
               <?php


               if (isset($airlines))
               {
                   foreach ($airlines as $airline)
                   {
                       echo "
            <tr> 
                <td>$airline->id_airline </td>
                <td>$airline->name </td>
            </tr>";
                   }
               }

               ?>
               </tbody>
           </table>
           <a href="./add-airline" class="btn btn-primary">Add Airline</a>
       </div>
   </div>

    <?php

    if(isset($success))
    {
        echo "<p  class='alert alert-success'> $success </p>";
    }


    ?>

<?php
    require_once (__DIR__.'/../../includes/footer.php')
?>