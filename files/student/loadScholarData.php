<?php

/*
 * To Get Data of Scholar No
 * By Abhishek K. Sharma
 * Dated : 29-SEP-2015.
 */

//call the main config file, functions file and header

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;

$scholarlist="<ul>";
$sqlString="SELECT studentid,scholarnumber, CONCAT(firstname,' ',middlename,' ',lastname) as name  
        FROM tblstudent WHERE scholarnumber like('".$_POST['keyword']."%') AND status=1 AND deleted!=1 "
        . "AND instsessassocid=".$_POST['instid'];

$result=  dbSelect($sqlString);
$scholarlist="<ul class='list-group'>";
if (mysqli_num_rows($result)>0) {
    while ($row= mysqli_fetch_assoc($result)) {
        $scholarlist.= "<li class='list-group-item'>
            <a href='#' onmousedown='selectItem(\"$row[scholarnumber]\",\"$row[studentid]\")'>".$row['scholarnumber']." | ".strtoupper($row['name']). " </li>" ;
    }

    $scholarlist.="</ul>";
} else {
    $scholarlist="<br/><span class='alert alert-danger' >No Matching Record Found</span>";
}
echo $scholarlist;
