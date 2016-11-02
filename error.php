<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: header page for the app
 * Updates here:
 */

// check if the user is logged in and allowed here
$bcPage = "Application Error";
include_once "./config/config.php";

?>
<link href="<?php echo DIR_ASSET; ?>/css/bootstrap-3.2.0.min.css" rel="stylesheet" type="text/css"/>
 <link href="<?php echo DIR_ASSET; ?>/css/font-awesome.min.css" rel="stylesheet">
<script src="<?php echo DIR_ASSET; ?>/js/bootstrap.js" type="text/javascript"></script>
<script src="<?php echo DIR_ASSET; ?>/js/jquery-3.1.1.js" type="text/javascript"></script>
<script src="<?php echo DIR_ASSET; ?>/js/bootstrap.js" type="text/javascript"></script>

    <div class="container">
        <div class="alert alert-danger">
            <h1> Oops...</h1>
            <p> Sorry, an unexpected error has occured. </p> 
            <p>  We are terribly sorry for this. However, the technical team has been notified and they will 
                attend to it ASAP.  </p>
            <p>
                If you wish to restart please click here or go back. 
            </p>
           
        </div>
        <?php if(DEVELOPMENT_ENVIRONMENT) { ?>
        <button data-toggle="collapse" data-target="#error" class="btn btn-danger">Show Error 
            <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
  
         <div id="error" class="collapse"><br>
             <li>Error Date: <strong><?= $_GET[0] ?></strong> <br></li> 
                <li>Type:  <strong> <?= $_GET[2] ?></strong> <br></li>   
                <li>Message: <strong> <?= $_GET[3] ?></strong> <br></li>
                <li>Page:  <strong> <?= $_GET[4] ?> </strong><br></li>   
                <li>Line No: <strong><?= $_GET[5] ?> </strong><br></li>
         </div><br><br>
         <?php } ?>
        <a href="javascript:history.go(-1)" class="btn btn-primary">Go to Previous Page</a>
        <a href="<?php echo DIR_FILES; ?>/dashboard.php" class="btn btn-success">Go to Dashboard</a>
        
    </div>
