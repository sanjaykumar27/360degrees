<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */

require_once "../config/config.php";
require_once '../lib/reportfunctions.php';
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

if (isset($_GET) && !empty($_GET)) {
    $qryString = '&' . http_build_query(cleanvar($_GET));
} else {
    $qryString = '';
}
?>
<div class="container">
    <?php renderMsg(); ?>
    <div class="span10">

        <form action="" method="GET" id="imform" name="myForm" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1"
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search">
                                <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></a> 
                        </span>   
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">Student First Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname" tabindex="2"
                               value ="<?php echo submitFailFieldValue("studentname"); ?>">
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->
            </div>     

            <span class='clearfix'>&nbsp;<br></span>

            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid"  class="form-control" tabindex="3" >
                            <?php echo populateSelect("classname", submitFailFieldValue("class")); ?>
                        </select>
                    </div>
                </div> 

                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid"  class="form-control" tabindex="4">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("section")); ?>
                        </select>
                    </div>
                </div>

                <span class="clearfix">&nbsp;</span>

                <div class="row"> 
                    <div class="controls" align="right">
                        <div class='col-lg-6'>
                            <button name='reset' value="Reset" class="btn " tabindex="6">Cancel</button>
                            <button name='search' value="search" class="btn btn-success" tabindex="5">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<span class="clearfix">&nbsp;</span>
<span class="clearfix">&nbsp;</span>
<?php
if (isset($_GET['search'])) {
    $studentDetails = getStudentFeeRuleReport(); ?>
    <div id="showreport" name="showreport" >
        <?php if ($studentDetails != 0) {
        ?>
            <div class="container">
                <table class="table table-hover table-bordered ">
                    <thead>
                        <tr >
                            <th>S.No</th>
                            <th>Scholar No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Fee Rule</th>
                            <th>Fee Installments</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $totalrows = $studentDetails['totalrows'];
        unset($studentDetails['totalrows']);
        $sno = 1;
        foreach ($studentDetails as $key => $value) {
            $studentName = $value['firstname'] . " " . $value['middlename'] . " " . $value['lastname'];
            $installments = getInstallmentNumber($value['classid'], $value['installment']); 
            $installments =  implode(',', array_unique(explode(',', $installments)));
            $installmentNo = $installments; ?>
                            <tr>
                                <td> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $sno; ?></a></td>
                                <td> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $value['scholarnumber']; ?></a></td>
                                <td> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo ucwords($studentName); ?></a></td>
                                <td> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo($value['classname'] . " - " . $value['sectionname']); ?></a></td>                 
                                <td width="450"> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $value['feerulename']; ?></a></td>
                                <td> <a href="../files/student/studentFees.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $installmentNo; ?></a></td>
                            </tr>
                            <?php $sno++;
        } ?>

                    </tbody>

                </table>
                <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
                    <a href="studentfeerulePDF.php?action=pdf<?php echo $qryString; ?>"> 
                        <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
                    <a href="studentfeerulePDF.php?action=xls<?php echo $qryString; ?>"> 
                        <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
                </div>

                <div class="col-lg-6" style="text-align: right; padding-right: 0px;">
        <?php getPagination($totalrows, ROW_PER_PAGE); ?>
                </div>
            </div>

    <?php 
    } else {
        ?> 
            <div class="container">
                <div class="alert alert-danger">
                    <p> 
                        No record(s) found for your selected crieteria. Please change the search criteria and try again !
                    </p>
                </div>    
            </div>
    <?php 
    } ?> 
    </div>
<?php 
} ?>
</div>
<?php
require VIEW_FOOTER;

function getStudentFeeRuleReport()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $studentDetails = array();
    $detailsArray = cleanVar($_REQUEST);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $sql = "SELECT     t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname,
            t5.feeruleamount, t8.classname, t9.sectionname, t8.classid,
            GROUP_CONCAT(t3.installment) AS installment, t4.feerulename
             
            FROM `tblstudent` AS t1,
            `tblstudfeeruleassoc` AS t2,
            `tblstudfeeruleinstasssoc` AS t3,
            `tblfeerule` AS t4,
            `tblfeeruledetail` AS t5,
            `tblstudentacademichistory` AS t6,
            `tblclsecassoc` AS t7,
            `tblclassmaster` AS t8,
            `tblsection` AS t9

            WHERE t1.instsessassocid = '$instsessassocid'
            AND t1.studentid = t2.studentid
            AND t2.studfeeruleassocid = t3.studfeeruleassocid
            AND t2.feeruleid = t4.feeruleid
            AND t4.feeruleid = t5.feeruleid
            AND t1.studentid = t6.studentid
            AND t6.clsecassocid = t7.clsecassocid
            AND t7.classid = t8.classid
            AND t7.sectionid = t9.sectionid
            AND t3.status = 1
            AND t1.status = 1
            AND t1.tcissued != 1
            GROUP BY  t1.studentid";

    if (isset($detailsArray['scholarnumber']) && !empty($detailsArray['scholarnumber'])) {
        $sql .=" AND t1.scholarnumber LIKE '$detailsArray[scholarnumber]%' ";
    }

    if (isset($detailsArray['studentname']) && !empty($detailsArray['studentname'])) {
        $studentName = explode(" ", $_REQUEST['studentname']);

        if (count(array_keys($studentName)) == 1) {
            $sql .= " AND UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')";
        }
        if (count(array_keys($studentName)) == 2) {
            $sql .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                          OR UPPER(t1.lastname)  LIKE ('" . strtoupper(trim($studentName[1])) . "%'))";
        }
        if (count(array_keys($studentName)) == 3) {
            $sql .= " AND ( UPPER(t1.firstname) LIKE ('" . strtoupper(trim($studentName[0])) . "%')
                          OR UPPER(t1.middlename) LIKE ('" . strtoupper(trim($studentName[1])) . "%') 
                          OR UPPER(t1.lastname) LIKE ('" . strtoupper(trim($studentName[2])) . "%') )";
        }
    }

    if (isset($detailsArray['classid']) && !empty($detailsArray['classid'])) {
        $sql .=" AND t7.classid = '$detailsArray[classid]' ";
    }

    if (isset($detailsArray['sectionid']) && !empty($detailsArray['sectionid'])) {
        $sql .=" AND t9.sectionid = '$detailsArray[sectionid]' ";
    }

    $sql .=" ORDER BY t3.installment, t8.classid, t9.sectionid, t1.firstname  ASC";
    $finalSql = $sql . " LIMIT " . $startPage . ',' . ROW_PER_PAGE;
    
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentDetails[] = $row;
        }
        $studentDetails['totalrows'] = mysqli_num_rows(dbSelect($sql));
        
        return $studentDetails;
    } else {
        return 0;
    }
}
