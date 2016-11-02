<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au 
 * Page details here: Page to import student from csv file
 * Updates here: 
 */

/* Assign the breadcrumb page name for current page */
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<div class="container">
    <form action="" method="post" id="imform" enctype="multipart/form-data">
        <div class="col-lg-4">
            <label for="csvfilename"> C.S.V File </label>
            <input class="form-control" type="file" name="csvfilename" id="csvfilename" >
        </div>

        <div class="col-lg-4">
            <label for="tblName"> Import Data </label>
            <select class="form-control" name="tblName" id="tblName" >
                <option value="">-Select One-</option>
                <option value="ImportStudent">Import Student Details</option>
                <option value="studentFees">Import Student Fees Details</option>


            </select>
        </div>

        <span class="clearfix"><p>&nbsp;</p></span> 
        <span class="clearfix"><p>&nbsp;</p></span> 

        <div class="controls" align="center"> 
            <input id="clearDiv" type="reset"  value="Cancel" class="btn">
            <input type="submit" id="save"  name="save" value="Submit" class="btn btn-success">
        </div>

    </form>


</div>


<?php



function getStdid($schno) {
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT t1.studentid, t2.clsecassocid
            FROM `tblstudent` as t1, 
            `tblstudentacademichistory` as t2
            WHERE t1.scholarnumber = '$schno'
            AND t1.studentid=t2.studentid
            AND t1.instsessassocid='$instsessassocid'";
   
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $studentDetails['studentid'] = $row['studentid'];
        $studentDetails['clsecassocid'] = $row['clsecassocid'];
        return $studentDetails;
    } else
    //for all empty records, store the details in a file to be confirmed later. 
    //before return 0. 
        $customErrMsg = $schno;

    $customErrMsg = "$schno\n\n";
    $fileHandler = fopen("/opt/lampp/htdocs/360/error/import_error.txt", "a+");
    fwrite($fileHandler, $customErrMsg);

    return 0;
}


function getClassid($clssessec) {

    $instsessassocid = $_SESSION['instsessassocid'];
    $installmentpaid = array();
    $sql = "SELECT t1.classid , t2.classdisplayname
             FROM `tblclsecassoc` as t1,
             `tblclassmaster` as t2
            WHERE t1.instsessassocid ='$instsessassocid'
            AND t1.clsecassocid = '$clssessec'
            AND t1.classid = t2.classid  ";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $classdetails['classid'] = $row['classid'];
        $classdetails['classname'] = $row['classdisplayname'];

        return $classdetails;
    }
}

function getDuedate($classid, $installmentNumber) {

    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT DISTINCT (t1.duedate)  
            FROM `tblfeestructuredetails` as t1, 
            `tblfeestructure` as t2
            WHERE t2.classid = '$classid'
            AND t1.feestructureid=t2.feestructureid
            AND t2.instsessassocid='$instsessassocid'";

    $result = dbSelect($sql);
    $i = 1;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $duedates[$row['duedate']] = romanNumerals($i);
            $i++;
        }

        $duedate = array_search($installmentNumber, $duedates); 
        return $duedate;
    }
}
?>