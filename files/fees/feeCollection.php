<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
require_once '../../lib/reportfunctions.php';
?>

<script >
    var TSort_Data = new Array('displaytable', 'h', 'h', 'h', 'h');
    tsRegister();

    function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);

        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);

    }
</script>
<div class="container">
    <div class="span10">
        <?php renderMsg(); ?>
        <form action="" method="GET" id="imform" name="myForm" onsubmit="return validateForm()">

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1" 
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search">
                                <span class="glyphicon glyphicon-search" name="search" id="search" value='Search' > </span></a> 
                        </span>   
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Student Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname" tabindex="2" 
                               value ="<?php echo submitFailFieldValue("studentname"); ?>">
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->
            </div>

            <span class="clearfix">&nbsp;<br></span>

            <div class='row'>
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id=""  class="form-control" tabindex="3" >
                            <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                        </select>
                    </div>
                </div> 

                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid"  class="form-control" tabindex="4">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
                        </select>
                    </div>
                </div>
            </div>
            <span class="clearfix">&nbsp;<br></span>
            <div class="row">
                <div class='control' align='center'>
                    <button name='reset' value="Reset" class="btn ">Cancel</button>
                    <button name='search'  id="search"  value="Search" class="btn btn-success">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<span class="clearfix">&nbsp;<br></span>
<?php
if (isset($_GET['search'])) {
    showSelectStudent();
    
}
require VIEW_FOOTER;

function studentdetails()
{
    $details = cleanVar($_GET);
    $studentdetails = array();
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $instsessassocid = $_SESSION['instsessassocid'];
    $sqlVar = "AND";
    $sql = "SELECT t1.studentid ,t1.scholarnumber, t1.firstname , t1.middlename ,t1.lastname,t3.classid, t4.sectionid,
        t3.classdisplayname, t4.sectionname, t1.datecreated,
        t7.parentfirstname, t7.parentmiddlename, t7.parentlastname
        
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6,
        `tblparent` AS t7,
        `tbluserparentassociation` AS t8
        
        WHERE t1.instsessassocid = $instsessassocid
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t7.parentid = t8.parentid
        AND t1.status != 0 
        AND t1.tcissued != 1  ";
    if (!empty($details['scholarnumber'])) {
        $sql .= "$sqlVar t1.scholarnumber  LIKE '$details[scholarnumber]%'";
    }
    if (!empty($details['studentname'])) {
        $sql .= " $sqlVar t1.firstname LIKE '$details[studentname]%'";
    }
    if (!empty($details['classid'])) {
        $sql .= " $sqlVar   t5.classid = '$details[classid]' ";
    }
    if (!empty($details['sectionid'])) {
        $sql .= " $sqlVar   t5.sectionid = '$details[sectionid]' ";
    }

    $finalSql = $sql . " GROUP BY t1.studentid ORDER BY t3.classid, t4.sectionid, t1.firstname ASC  LIMIT " . $startPage . ',' . ROW_PER_PAGE;
  // echoThis($finalSql);die; 
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $row;
        }
        $studentdetails['totalrows'] = mysqli_num_rows(dbSelect($sql . " GROUP BY t1.studentid"));
        
        return $studentdetails;
    } else {
        return 0;
    }
}

function showSelectStudent()
{
    $studentdetails = studentdetails();
    
    if ($studentdetails == 0) {
       // echo "<div class=\"container\"><div class=\"alert alert-danger\" role=\"alert\">
         //           No record(s) found as per your criteria. Please change your criteria and try again.
           //         </div></div>";
           addError("custom");
    } else {
        $strTable = "<div class=\"container\" id=\"printdiv\">
             <table class=\"table table-bordered table-hover \" id=\"displaytable\">
                <thead> 
                    <tr >
                        <th>S.No</th>
                        <th>Scholar No</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Due Amount </th>
                        <th>Action</th>
                    </tr>
                </thead> 
        <tbody>";
        $totalStudents = $studentdetails['totalrows'];
       
        $dueFees = 0;
        unset($studentdetails['totalrows']);
        $j = 1;
        foreach ($studentdetails as $key => $detailsvalue) {
            $feeComponentdetails = feeComponentsSql($detailsvalue['studentid'], $detailsvalue['classid']);

            $sectionName = strtoupper($detailsvalue['sectionname']);
            $studentName = ucwords(strtolower($detailsvalue['firstname'] . ' ' . $detailsvalue['middlename'] . ' ' . $detailsvalue['lastname']));
            $parentName = ucwords(strtolower($detailsvalue['parentfirstname'] . ' ' . $detailsvalue['parentmiddlename'] . ' ' . $detailsvalue['parentlastname']));
            $dueFees = $feeComponentdetails['totalfeedue'];
            $otherFees = OtherFees($feeComponentdetails['duedate'], $dueFees);
            $dueFees += $otherFees;
            if (!empty($feeComponentdetails['totalfeedue']) && !empty(getTransportFees($detailsvalue['studentid']))) {
                $dueFees += getTransportFees($detailsvalue['studentid']);
            }

            $dueFees = formatCurrency($dueFees);
            $strTable .="
            <tr>  
                <td class=\"col-md-1\"> $j</td>
                <td class=\"col-md-2\"><a href=\"../../files/student/studentPersonal.php?sid=$detailsvalue[studentid]&mode=edit\">$detailsvalue[scholarnumber]</a></td>
                <td class=\"col-md-3\"> <a href=\"../../files/student/studentPersonal.php?sid=$detailsvalue[studentid]&mode=edit\">$studentName($detailsvalue[classdisplayname] - $sectionName)</a> </td>
                <td class=\"col-md-2\"><a href=\"../../files/student/studentParent.php?sid=$detailsvalue[studentid]&mode=edit\"> $parentName</a> </td>
                <td class=\"col-md-2\"><a href=\"../../files/student/studentFeeDetails.php?sid=$detailsvalue[studentid]&mode=edit\"> $dueFees </a> </td>
                ";

            if (!empty($feeComponentdetails['totalfeedue'])) {
                $status = "<button class=\"btn btn-warning\" onClick=\"popUp('feeCollectionProcessing.php?studentid=" . $detailsvalue['studentid'] . "&pop-up=y',1100,500)\" > Pay Due Fees</button>";
                $strTable .=" <td class=\"col-md-1\"> $status</td>";
            } else {
                $status = "<a href=\"../../files/student/studentFeeDetails.php?sid=$detailsvalue[studentid]&mode=edit\"><button class=\"btn btn-success\" > No Fee Due</button></a>";
                $strTable .=" <td class=\"col-md-1\"> $status</td>
                                    </tr>";
            }
            $j++;
        }
        $strTable .= "</tbody></table><div class=\"clearfix\"></div>";
        echo $strTable;
        echo "<div class=\"col-sm-6\"></div><div class=\"col-sm-6\" style=\"text-align: right; padding: 0px\">" . getPagination($totalStudents, ROW_PER_PAGE) . "</div>";
    }
}

function createInstallmentArray($HtmlArray)
{
    $newOptions = array();
    $totalamount = array();
    foreach ($HtmlArray as $option) {
        $duedate = $option['duedate'];
        $feecomponents = $option['feecomponent'];
        $amount = $option['amount'];
        $newOptions[$duedate][$feecomponents] = $amount;
    }
    foreach ($newOptions as $key => $value) {
        $total = 0;
        $total = array_sum($value);
        $newOptions[$key]['totalamount'] = $total;
    }
    return $newOptions;
}

function collectedfeeSql($id)
{
    $studentid = $id;
    $collectedfeedetails = array();
    $today = date('Y-m-d');
    $sql = " SELECT *
       FROM `tblfeecollection` AS t1,
      `tblfeecollectiondetail` AS t2

       WHERE t1.studentid = $studentid
       AND t1.feecollectionid = t2.feecollectionid
       AND t2.feestatus = 1
      ";
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $collectedfeedetails[$row['feeinstallment']] = $row;
    }
    return $collectedfeedetails;
}

function feeComponentsSql($studentid, $classid)
{
    $feedetails = array();
    $totalfeedue = 0;
    $duedate = '';
    $currentDate = date('Y-m-d');
    $feeruledetails = feeRuleSql($studentid);
    $feeRuleInstallment = getInstFeeRuleAssoc($studentid);

    if (!empty($feeruledetails)) {
        foreach ($feeruledetails as $key => $value) {
            $feeruleamount[] = $feeruledetails[$key]['feeruleamount'];
            $feerulecomponents[] = $feeruledetails[$key]['feecomponent'];
            $feerulemode[] = $feeruledetails[$key]['feerulemodeid'];
            $feeruletype[] = $feeruledetails[$key]['feeruletype'];
        }
    }

    $sql = " SELECT t1.feestructureid, t3.feecomponent, t2.feestructureid, t2.amount , 
                t2.duedate, t2.isrefundable, t2.frequency 
                FROM `tblfeestructure` AS t1,
		`tblfeestructuredetails` AS t2,
		`tblfeecomponent` AS t3
		  
               WHERE t1.classid = $classid
               AND t1.feestructureid = t2.feestructureid
               AND t1.feecomponentid = t3.feecomponentid
               AND t1.status != 0 
               AND t1.deleted != 1
               AND t2.duedate <= '$currentDate'
               AND t2.duedate NOT IN(
               
                    SELECT t3.feeinstallment FROM `tblfeecollection` AS t1, 
                    `tblfeecollectiondetail` AS t2,
                    `tblfeeinstallmentdates` AS t3
                    
                    WHERE t1.studentid = $studentid
                    AND t1.feecollectionid = t2.feecollectionid
                    AND t2.feecollectiondetailid = t3.feecollectiondetailid
                    AND (t2.feestatus = 1 OR t2.feestatus = 0)
                    )
               ORDER BY t2.duedate ASC 
           ";
   
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) != 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[] = $row;
        }
    }
    if (!empty($feeruledetails)) {
        foreach ($feeruledetails as $key => $value) {
            foreach ($feedetails as $k => $val) {
                if ($value['feecomponent'] == $val['feecomponent'] && in_array($val['duedate'], $feeRuleInstallment)) {
                    $feedetails[$k]['originalamount'] = $val['amount'];
                    $val['amount'] = updateFees($value['feeruletype'], $value['feerulemodeid'], $val['amount'], $value['feeruleamount']);
                    $feedetails[$k]['amount'] = $val['amount'];
                } else {
                    $feedetails[$k]['amount'] = $val['amount'];
                    $feedetails[$k]['originalamount'] = $val['amount'];
                }
            }
        }

        foreach ($feedetails as $fk => $fvalue) {
            $totalfeedue += $fvalue['amount'];
            $duedate = $fvalue['duedate'];
        }
        $feedetails['totalfeedue'] = $totalfeedue;
        $feedetails['duedate'] = $duedate;
        return($feedetails);
    } else {
        foreach ($feedetails as $key => $fvalue) {
            $feedetails[$key]['originalamount'] = $fvalue['amount'];
            $totalfeedue += $fvalue['amount'];
            $duedate = $fvalue['duedate'];
        }
        $feedetails['totalfeedue'] = $totalfeedue;
        $feedetails['duedate'] = $duedate;
        return($feedetails);
    }
}

function feeRuleSql($studentid)
{
    $sql = "SELECT *
        FROM `tblfeerule` AS t1,
        `tblfeeruledetail` AS t2, 
        `tblstudfeeruleassoc` AS t3,
        `tblfeecomponent` AS t4

        WHERE t3.studentid = $studentid
        AND t1.feeruleid = t2.feeruleid 
        AND t1.feeruleid = t3.feeruleid
        AND t2.feecomponentid = t4.feecomponentid
        AND t1.feerulestatus = 1
        AND t1.deleted != 1
        ";

    if (($result = dbSelect($sql)) && (($num_row = mysqli_num_rows($result)) != 0)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feeruledetails[] = $row;
        }
        return($feeruledetails);
    }
}

function updateFees($type, $mode, $amount, $value)
{
    if ($type == 261) {
        if ($mode == 263) {
            $amount = ($amount - ($amount * $value / 100));
            return $amount;
        } else {
            $amount = ($amount - $value);
            return $amount;
        }
    } else {
        if ($mode == 263) {
            $amount = ($amount + ($amount * $value / 100));
            return $amount;
        } else {
            $amount = ($amount + $value);
            return $amount;
        }
    }
}

function getInstFeeRuleAssoc($studentid)
{
    $installmentArray = array();

    $sql = "SELECT t2.installment
              FROM  `tblstudfeeruleassoc` AS t1,
              `tblstudfeeruleinstasssoc` AS t2
              WHERE t1.studentid = $studentid
              AND t1.studfeeruleassocid = t2.studfeeruleassocid
              AND t2.status = 1 ";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $installmentArray[] = $row['installment'];
        }
        return $installmentArray;
    } else {
        return 0;
    }
}
