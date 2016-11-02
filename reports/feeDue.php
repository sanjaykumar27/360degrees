<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */

//call the main config file, functions file and header
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<script type="text/javascript">
    $("#siblingYes").on('click', function () {
        $('#displayresult').modal('show')
    });

</script>

<div class ="container">
    <div class="span10">
        <?php
        $studentdetails = studentDetailsSql();
        $collectedfee = collectedfeeSql();
        $totalinstallmentArray = generateMonthArray();
        $collectedInstallmentArray = collectedFeeMonthArray();
        $siblingdetails = siblingDetails();
        $siblingCol = "<input type=\"button\" id=\"siblingNO\" name=\"siblingNO\" class=\"btn\" value=\"None\">";
        if (!empty($siblingdetails)) {
            $siblingCol = "<input type=\"button\" id=\"siblingYes\" name=\"siblingYes\" class=\"btn btn-success\" value=\"Yes\" 
           data-toggle=\"modal\" data-target=\"#myModal\">";
        }
        ?>
        <h3>  Student Details </h3>
        <table  class="table table-hover table-bordered">
            <tr >
                <th>Scholarno</th>
                <th>Student Name</th>
                <th>Fathers' Name</th>
                <th>Class</th>
                <th>Enrolled In</th>
                <th>Siblings</th>
            </tr>

            <tr>
                <td><?php echo($studentdetails['scholarnumber']) ?></td>
                <td><?php echo($studentdetails['firstname'] . " " . $studentdetails['middlename'] . " " . $studentdetails['lastname']) ?></td>
                <td><?php echo($studentdetails['parentfirstname'] . " " . $studentdetails['parentmiddlename'] . " " . $studentdetails['parentlastname']) ?> </td>
                <td><?php echo($studentdetails['classdisplayname'] . " - " . strtoupper($studentdetails['sectionname'])) ?></td>
                <td><?php echo($studentdetails['sessionname']) ?></td>
                <td><?php echo($siblingCol) ?></td>
            </tr>


        </table>
        <span class="clearfix">&nbsp;<br></span>
        <table  class="table table-hover table-bordered">
            <tr >
                <th>Scholar no</th>
                <th>Student Name</th>
                <th>Fathers' Name</th>
                <th>Class</th>
                <th>Enrolled In</th>
                <th>Siblings</th>
            </tr>
        </table>

        <div class="col-lg-4">
            <label> Due Installments  </label><br><br>
            <?php
            if (!empty($collectedInstallmentArray)) {
                $totalAmount = 0;
                foreach ($totalinstallmentArray as $k => $val) {
                    if (!in_array($k, $collectedInstallmentArray)) {
                        echo($k . "<br><br>");
                    }
                } ?>
            </div>

            <div class="col-lg-4">
                <label>Amount(in Rs) </label><br><br>

                <?php
                foreach ($totalinstallmentArray as $k => $val) {
                    if (!in_array($k, $collectedInstallmentArray)) {
                        $totalAmount += $val;
                        echo("Rs " . $val . "</b><br><br>");
                    }
                } ?>
            </div> 

            <span class="clearfix">&nbsp;</span>

            <div class="row">
                <div class="col-lg-9">
                    <div class="controls" align="Right">
                        <label>Total Fee Due &nbsp &nbsp; </label>
                        <?php echo("Rs " . $totalAmount); ?>
                        <?php

            } else {
                echo("<div class=\"alert alert-warning\">
                        <strong>Warning!&nbsp;&nbsp;All fee installments of your ward are due. 
                            Kindly pay  fee-installments regularly to avoid inconvenience in future
                </div>");
            }
                    ?>  
                </div>
            </div>
        </div>

    </div><!-----------span11 closed------->
</div><!-----------container closed------->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Siblings Details</h4>
            </div>

            <div class="modal-body">
                <table class="table table-condensed table-hover" > 
                    <tr class="info">
                        <th align="center">S.No</th>
                        <th align="center">Scholar No.</th>
                        <th align="center">First/Last Name</th>
                    </tr> 
                    <?php
                    $details = siblingDetails();
                    $i = 1;
                    if (!empty($details)) {
                        foreach ($details as $key => $value) { //echoThis($details); die;
                            ?>
                            <tr>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $i ?></td></a>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $value['scholarnumber'] ?></td></a>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo($value['firstname'] . " " . $value['middlename'] . " " . $value['lastname']) ?></td></a>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?>   
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once VIEW_FOOTER;

function studentDetailsSql()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);

        $sql = "SELECT  *
              FROM `tblstudent` AS t1, 
              `tblstudentdetails` AS t2, 
              `tblclassmaster` AS t3,
              `tblsection` AS t4,
              `tbluserparentassociation` AS t5,
              `tblparent` AS t6,
              `tblinstsessassoc` As t7,
              `tblinstitute` AS t8,
              `tblacademicsession` AS t9,
              `tblstudentacademichistory` AS t10,
              `tblclsecassoc` AS t11
          
                WHERE t1.studentid = $studentid
                AND t1.studentid = t2.studentid
                AND t10.studentid = t1.studentid
                AND t10.clsecassocid = t11.clsecassocid
                AND t3.classid = t11.classid 
		AND t11.sectionid = t4.sectionid
                AND t1.studentid = t5.studentid
                AND t5.parentid = t6.parentid 
                AND t7.instsessassocid = $instsessassocid
                AND t7.instituteid = t8.instituteid
                AND t7.academicsessionid = t9.academicsessionid
	";
        if ($result = dbSelect($sql)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
    }
}

function siblingDetails()
{
    $userid = $_SESSION['userid'];
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);

        $sql = "SELECT t1.scholarnumber,t1.studentid, t2.parentid, t3.parentfirstname, t3.parentlastname,
                t4.classname, t5.sectionname
          
            FROM `tblstudent` AS t1, 
            `tbluserparentassociation` AS t2,
            `tblparent` AS t3,
            `tblclassmaster` AS t4,
            `tblsection` AS t5,
            `tblclsecassoc` AS t6,
            `tblstudentacademichistory` AS t7
		  
            WHERE t2.userid = $userid
            AND t1.studentid = $studentid
            AND t1.studentid = t2.studentid
            AND t2.parentid = t3.parentid
            AND t7.clsecassocid = t6.clsecassocid
            AND t6.classid = t4.classid
            AND t5.sectionid = t6.sectionid
	";
        $siblingdetail = array();
        $result = dbSelect($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($studentid != $row['studentid']) {
                $siblingdetail[] = $row;
            }
        }

        if ($siblingdetail) {
            return $siblingdetail;
        }
    }
}

function generateMonthArray()
{
    $totalinstallmentamount = 0;
    $j = 0;
    $totaldue = 0;
    $feedetails = feeComponentsSql();

    if (!empty($feedetails)) {
        foreach ($feedetails as $key => $value) {
            if ($feedetails[$key]['duedate'] < date('Y-m-d')) {
                $month[] = date('m', strtotime($feedetails[$key]['duedate']));
                $monthname[] = date('F', strtotime($feedetails[$key]['duedate']));
                $monthArray = array_unique($month);
                $monthNames = array_unique($monthname);
            }
        }

        foreach ($monthNames as $k => $val) {
            $monthNameArray[$j] = $val;
            $j++;
        }
    }
    $arr = createInstallmentAmountArray($monthArray, $month);
    foreach ($arr as $mk => $mval) {
        $returnArray[$monthNameArray[$mk]] = $mval;
    }

    return $returnArray;
}

function collectedFeeMonthArray()
{
    $collectedfee = collectedfeeSql();
    $CollectedMonthNameArray = array();
    if (!empty($collectedfee)) {
        foreach ($collectedfee as $key => $value) {
            $CollectedMonth[] = date('m', strtotime($collectedfee[$key]['feeinstallment']));
            $CollectedMonthname[] = date('F', strtotime($collectedfee[$key]['feeinstallment']));
        }
        $CollectedMonthArray = array_unique($CollectedMonth);
        $CollectedMonthNames = array_unique($CollectedMonthname);
        $CollectedMonthnameArray = $CollectedMonthNames;

        $j = 0;

        foreach ($CollectedMonthNames as $k => $val) {
            $CollectedMonthNameArray[$j] = $val;
            $j++;
        }
    }
    return $CollectedMonthNameArray;
}

function createInstallmentAmountArray($MonthArray, $month)
{
    $i = 0;
    $feedetails = feeComponentsSql();
    $totalinstallmentamount = '';
    foreach ($MonthArray as $k => $val) {
        foreach ($month as $key => $value) {
            if ($value == $val) {
                $totalinstallmentamount += $feedetails[$key]['amount'];
            }
        }
        $installmentamount[$i] = $totalinstallmentamount;
        $totalinstallmentamount = 0;
        $i++;
    }
    return $installmentamount;
}

function collectedfeeSql()
{
    $collectedfeedetails = array();

    if (isset($_GET['studentid']) && is_numeric($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
        if (isset($_GET['date1']) && isset($_GET['date2'])) {
            $startdate = cleanVar($_GET['date1']);
            $enddate = cleanVar($_GET['date2']);
            $sql = "SELECT *

		 FROM `tblstudent` AS t1, 
		`tblstudentdetails` AS t2,
		`tblclassmaster` AS t3,
		`tblsection` AS t4,
		`tblfeecollection` AS t5,
		`tblfeecollectiondetail` AS t6,
                `tblstudentacademichistory` AS t7,
                `tblclsecassoc` AS t8

                WHERE t6.feeinstallment BETWEEN '$startdate' AND '$enddate'
                AND t1.studentid  = $studentid
                AND t3.classid = t8.classid 
                AND t8.sectionid = t4.sectionid
                AND t1.studentid = t5.studentid
                AND t5.feecollectionid = t6.feecollectionid 
                AND t1.studentid = t7.studentid
                AND t7.clsecassocid = t8.clsecassocid
            ";
        } else {
            $sql = "SELECT *
                FROM `tblstudent` AS t1, 
                `tblstudentdetails` AS t2,
                `tblclassmaster` AS t3,
                `tblsection` AS t4,
                `tblfeecollection` AS t5,
                `tblfeecollectiondetail` AS t6,
                `tblstudentacademichistory` AS t7,
                `tblclsecassoc` AS t8

                WHERE t1.studentid = $studentid
                AND t1.studentid = t2.studentid
                AND t1.studentid = t7.studentid
                AND t7.clsecassocid = t8.clsecassocid
                AND t3.classid = t8.classid 
                AND t8.sectionid = t4.sectionid
                AND t1.studentid = t5.studentid
                AND t5.feecollectionid = t6.feecollectionid ";
        }
        $result = dbSelect($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $collectedfeedetails[] = $row;
        }
        return $collectedfeedetails;
    }
}

function feeComponentsSql()
{
    if (isset($_GET['studentid']) && is_numeric($_GET['studentid'])) {
        $studentdetails = studentDetailsSql();
        $feeruledetails = feeRuleSql();
        if (!empty($feeruledetails)) {
            foreach ($feeruledetails as $key => $value) {
                $feeruleamount[] = $feeruledetails[$key]['feeruleamount'];
                $feerulecomponents[] = $feeruledetails[$key]['feecomponent'];
                $feerulemode[] = $feeruledetails[$key]['feerulemodeid'];
                $feeruletype[] = $feeruledetails[$key]['feeruletype'];
            }
        }
        $classid = $studentdetails['classid'];
        if (isset($_GET['date1']) && isset($_GET['date2'])) {
            $startdate = cleanVar($_GET['date1']);
            $enddate = cleanVar($_GET['date2']);

            $sql = " SELECT t1.feestructureid, t1.feecomponentid, t3.feecomponent, t2.feestructureid,
                t2.amount, t2.duedate, t2.isrefundable, t2.frequency 

                FROM `tblfeestructure` AS t1,
               `tblfeestructuredetails` AS t2,
               `tblfeecomponent` AS t3

                WHERE t2.duedate BETWEEN  '$startdate' AND ' $enddate'
                AND t1.classid = $classid
                AND t1.feestructureid = t2.feestructureid
                AND t1.feecomponentid = t3.feecomponentid
                ORDER BY t2.duedate ASC 
                ";
        } else {
            $sql = " SELECT t1.feestructureid, t1.feecomponentid, t3.feecomponent, t2.feestructureid, t2.amount, t2.duedate, 
            t2.isrefundable, t2.frequency 

            FROM `tblfeestructure` AS t1,
           `tblfeestructuredetails` AS t2,
           `tblfeecomponent` AS t3

            WHERE  t1.classid = $classid
            AND t1.feestructureid = t2.feestructureid
            AND t1.feecomponentid = t3.feecomponentid
            ORDER BY t2.duedate ASC 
    ";
        }
        $result = dbSelect($sql);
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $feedetails[] = $row;
            }
        }
        if (isset($feeruledetails)) {
            foreach ($feeruledetails as $key => $value) {
                foreach ($feedetails as $k => $val) {
                    if ($value['feecomponentid'] == $val['feecomponentid']) {
                        $val['amount'] = updateFees($value['feeruletype'], $value['feerulemodeid'], $val['amount'], $value['feeruleamount']);
                        $feedetails[$k]['amount'] = $val['amount'];
                    } else {
                        $feedetails[$k]['amount'] = $val['amount'];
                    }
                }
            }

            return($feedetails);
        } else {
            return($feedetails);
        }
    } else {
        return 0;
    }
}

function feeRuleSql()
{
    if (isset($_GET['studentid']) && is_numeric($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
        $sql = "SELECT *
		
	FROM `tblstudfeeruleassoc` AS t1, 
	`tblfeeruledetail` AS t2, 
	`tblfeerule` AS t3,
        `tblfeecomponent` AS t4
			
	WHERE t1.studentid = $studentid 
	AND t1.feeruleid = t2.feeruleid 
        AND t3.feeruleid = t2.feeruleid
        AND t2.feecomponentid = t4.feecomponentid    ";

        if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $feeruledetails[] = $row;
            }
            return($feeruledetails);
        }
    } else {
        return 0;
    }
}

function updateFees($type, $mode, $amount, $value)
{
    if ($type == 6) {
        if ($mode == 4) {
            $amount = ($amount - ($amount * $value / 100));
            return $amount;
        } else {
            $amount = ($amount - $value);
            return $amount;
        }
    } else {
        if ($mode == 4) {
            $amount = ($amount + ($amount * $value / 100));
            return $amount;
        } else {
            $amount = ($amount + $value);
            return $amount;
        }
    }
}
