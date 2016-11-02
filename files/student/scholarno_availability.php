 <?php
 /*
* 360-Parent, part of 360 degree app, created to filter and clean the data.
* Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
* Page details here:
* Updates here: Return ajax response called from addStudent.php to check availability of student scholarnumber's
*/
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
 
    if (!empty($_GET["scholarnumber"])) {
        $sql = "SELECT * FROM `tblstudent` WHERE `scholarnumber` ='" . $_GET["scholarnumber"] . "'";
        $result = dbSelect($sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<span class='alert-danger'> Scholarnumber Not Available.</span>";
        } else {
            echo "<span class='text-success'> Scholarnumber Available.</span>";
        }
    }
