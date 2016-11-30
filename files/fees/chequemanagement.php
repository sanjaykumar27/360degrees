<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
 * Page details here: Details of fee paid by cheque
 * Updates here:
 */
/* Assign the breadcrumb page name for current page */
$bcPage = "Cheque Management";
/* bread crumb page variables ends */

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

if (isset($_GET['status']) && !isset($_GET['cb'])) {
    $feecollectionid = cleanVar($_GET['fid']);
    if ($_GET['status'] == 1) {
        $status = "1";
    } else {
        $status = "In Process";
    }
    $sql = array("UPDATE `tblfeecollectiondetail` 
        SET `feestatus`= '$status', `dateupdated` = CURRENT_TIMESTAMP
            
         WHERE `feecollectionid` = '$feecollectionid' ",
        "UPDATE `tblfeecheque` SET `chequestatus`= '$status', `dateupdated` = CURRENT_TIMESTAMP  WHERE `feecollectionid` = '$feecollectionid'"
    );

    $result = dbUpdate($sql);
}

if (isset($_GET['fcid']) && !empty($_GET['fcid']) && $_GET['cb'] == 1) {
    chequebounce();
}
?>

<script type='text/javascript' src='../../asset/js/gs_sortable.js'></script>
<script type="text/javascript">
    // This  code is used  for sorting the data inside the table using TSORT API...//
    var TSort_Data = new Array('displaytable', 'h', 'h', 'h');
    tsRegister();
</script>

<div class="container">
    <div class="span10">
        <?php renderMsg(); ?>
        <form action="" method="GET" id="imform">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Scholar No</span> 
                        <input type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1" 
                               value ="<?php echo submitFailFieldValue("scholarnumber"); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search" value='search'>
                                <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></a> 
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

            <span class="clearfix"> &nbsp;<br></span>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid" tabindex="2" class="form-control" tabindex="3" >
                            <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                        </select>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-3 col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Cheque Number</span> 
                        <input type="text" class="form-control" name="chequenumber" id="chequenumber" tabindex="4" 
                               value ="<?php echo submitFailFieldValue("chequenumber"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-3 col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Bank</span> 
                        <input type="text" class="form-control" name="bankname" id="bankname" tabindex="7" 
                               value ="<?php echo submitFailFieldValue("bankname"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

            </div>

            <span class="clearfix">&nbsp;<br></span>

            <div class="row">

                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date From</span> 
                        <input type="date" class="form-control" name="startdate" id="startdate" tabindex="5" 
                               value ="<?php echo submitFailFieldValue("startdate"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->

                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date To</span> 
                        <input type="date" class="form-control" name="enddate" id="enddate" tabindex="6" 
                               value ="<?php echo submitFailFieldValue("enddate"); ?>">

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-4 -->
            </div>

            <span class="clearfix">&nbsp; <br></span>

            <div class="row"> 
                <div class="controls" align="right">
                    <div class='col-lg-6 col-md-6'>
                        <button name='reset' value="Reset" class="btn " tabindex="9">Cancel</button>
                        <button name='search' value="search" class="btn btn-success" tabindex="8">Search</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<span class="clearfix"> &nbsp; <br></span>

<?php
if (isset($_GET['search']) && $_GET['search'] == 'search') {
    showChequeDetails();
}

require_once VIEW_FOOTER;

function getChequeDetails()
{
    $details = cleanVar($_REQUEST);
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    if (!isset($_SESSION['sessionstartdate']) && empty($_SESSION['sessionstartdate'])) {
        getSessionStartEndDate();
    }
    //assign the starts of the session date
    $rangeStartDate = $_SESSION['sessionstartdate'];

    $sql = "SELECT t1.studentid, t1.scholarnumber, t1.firstname, t1.middlename, t1.lastname, t3.feecollectionid, 
                t4.feecollectiondetailid, t5.chequedepositdate, t5.chequenumber, t5.bankname, t5.chequestatus,
                t8.classid, t8.classname, t9.sectionid, t9.sectionname, GROUP_CONCAT(t11.feeinstallment) AS paidinstallments
                
                FROM `tblstudent` AS t1,
                `tblstudentacademichistory` AS t2,
                `tblfeecollection` AS t3,
                `tblfeecollectiondetail` AS t4,
                `tblfeecheque` AS t5,
                `tblmastercollection` AS t6,
                `tblclsecassoc` AS t7,
                `tblclassmaster` AS t8,
                `tblsection` AS t9,
                `tblfeeinstallmentdates` AS t11
                
          
                WHERE t1.studentid =  t3.studentid
                AND t1.studentid = t2.studentid
                AND t3.feecollectionid = t4.feecollectionid
                AND t3.feecollectionid = t5.feecollectionid
                AND t4.feemodeid = t6.mastercollectionid
                AND t6.collectionname = 'CHEQUE'
                AND t2.clsecassocid = t7.clsecassocid
                AND t7.classid = t8.classid
             	AND t7.sectionid = t9.sectionid
                AND t4.feecollectiondetailid = t11.feecollectiondetailid
                AND (t4.feestatus = 0 OR  t4.feestatus = 2)
                AND t4.refundstatus = 0 
                
            ";

    if (!empty($details['studentname'])) {
        $sql .= "AND t1.firstname  LIKE '$details[studentname]%'";
    }
    if (!empty($details['classid'])) {
        $sql .= " AND t8.classid = '$details[classid]'";
    }
    if (!empty($details['scholarnumber'])) {
        $sql .= " AND t1.scholarnumber = '$details[scholarnumber]' ";
    }
    if (!empty($details['chequenumber'])) {
        $sql .= " AND t5.chequenumber LIKE '$details[chequenumber]&' ";
    }

    if (isset($details['startdate']) && !empty($details['startdate'])) {
        $rangeStartDate = $details['startdate'];
        $sql .= " AND t5.chequedepositdate >= '$rangeStartDate'";
    }

    if (isset($details['enddate']) && !empty($details['enddate'])) {
        //set the start of the session date, taken from Session
        $sql .= " AND t5.chequedepositdate <= '$details[enddate]'";
    }

    if (!empty($details['bankname'])) {
        $sql .= " AND t5.bankname LIKE '$details[bankname]%' ";
    }

    $sql .= " GROUP BY t5.chequenumber  LIMIT $startPage," . ROW_PER_PAGE;

    $result = dbSelect($sql);
    if ($result && mysqli_num_rows(dbSelect($sql)) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $chequedetails[] = $row;
        }
        $chequedetails['totalRows'] = mysqli_num_rows($result);
        return $chequedetails;
    } else {
        return 0;
    }
}

function showChequeDetails()
{
    $chequeDetails = getChequeDetails();
    if ($chequeDetails == 0) {
        $strHTML = "
              <div class=\"container\">
                <div class=\"span10\">
                    <div class=\"alert alert-danger\">
                        Sorry No record found, Please search again with correct search parameters
                </div>
                </div>
             </div>
                ";
    } else {
        $totalrecords = $chequeDetails['totalRows'];
        unset($chequeDetails['totalRows']);

        $strHTML = "
            <div class=\"container\">
            <table class=\"table table-hover table-bordered\" id=\"displaytable\">
            <thead>
                <tr>
                    <th>ScholarNo.</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Installments</th>
                    <th>Date Of Deposit</th>
                    <th>Cheque Number</th>
                    <th>Bank Name</th>
                    <th>Status</th>
                    <th>Cheque Bounce</th>
                </tr>
            </thead>
                ";

        foreach ($chequeDetails as $key => $value) {
            $statusStyle = '';
            $chqdepositdate = date('d/m/Y', strtotime($value['chequedepositdate']));
            $paidinstallments = explode(",", $value['paidinstallments']);
            $renderInst = $paidinstallments[0];
            $paidinstallment = getInstallmentNumber($value['classid'], $paidinstallments);
            $paidinstallment = rtrim($paidinstallment, ",");
            $status = 0;
            $chequeBounce = 'class="fa fa-toggle-off fa-2x" style="color:red;"';
            if ($value['chequestatus'] == "1") {
                $statusStyle = ' class="fa fa-toggle-on fa-2x" style="color:green;"';
            } elseif ($value['chequestatus'] == "2") {
                $statusStyle = 'class="fa fa-toggle-off fa-2x" style="color:red;"';
                $chequeBounce = 'class="fa fa-toggle-off fa-2x" style="color:green;"';
            } else {
                $statusStyle = 'class="fa fa-toggle-off fa-2x" style="color:red;"';
                $status = 1;
            }

            $strHTML .= "
                       <tr>
                            <td>$value[scholarnumber]</td>
                            <td><a href=\"../student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$value[firstname] $value[middlename] $value[lastname]</a></td>
                            <td><a href=\"../student/studentFeeDetails.php?sid=$value[studentid]&mode=edit\">$value[classname] - $value[sectionname]</a></td>
                            <td>$paidinstallment</td>
                            <td>$chqdepositdate</td>
                            <td>$value[chequenumber]</td>
                            <td>$value[bankname]</td>
                            <td><a href=\"chequemanagement.php?status=$status&fid=$value[feecollectionid]&search=search\"><i $statusStyle></i></a></td>
                            <td><a href=\"chequemanagement.php?studentid=$value[studentid]&fcid=$value[feecollectionid]&search=search&cb=1\"><i $chequeBounce></i></a></td>
                        </tr>
                    ";
        }

        $strHTML .= " </table>
               <span class=\"clearfix\">&nbsp;<br></span>
               
            <div class=\"col-sm-6\" style=\"text-align: right; padding: 0px\">";

        $strHTML .= getPagination($totalrecords, ROW_PER_PAGE) . "</div></div>";
    }
    echo $strHTML;
}

function getSessionStartEndDate()
{
    $sql = "SELECT  sessionstartdate, sessionenddate 
        
            FROM tblacademicsession as t1, 
            tblinstsessassoc as t2 
            
            WHERE t1.academicsessionid = t2.academicsessionid 
            AND t2.instsessassocid = $_SESSION[instsessassocid]";

    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);

    if (!isset($_SESSION['sessionstartdate']) && empty($_SESSION['sessionstartdate'])) {
        $_SESSION['sessionstartdate'] = $row['sessionstartdate'];
        $_SESSION['sessionenddate'] = $row['sessionenddate'];
    }
}

function chequebounce()
{
    $feecollectionid = cleanVar($_GET['fcid']);
    $instsessassocid = $_SESSION['instsessassocid'];
    $studentid = cleanVar($_GET['studentid']);
    // $feeinstallment = cleanVar($_GET['installment']);

    $getChqBouncedetails = mysqli_fetch_assoc(dbSelect("SELECT t1.feeotherchargesid,  t2.amount,t3.chequedepositdate
                            FROM `tblfeeothercharges` AS t1,
                            `tblfeeotherchargesdetails` AS t2,
                            `tblfeecheque` AS t3
                            WHERE t1.otherfeehead = 'Cheque Bounce'
                            AND t1.feeotherchargesid = t2.feeotherchargesid
                            AND t3.feecollectionid = '$feecollectionid'
                            "));
    $ChqBounceId = $getChqBouncedetails['feeotherchargesid'];
    $ChqBounceAmt = $getChqBouncedetails['amount'];
    $ChqDepositDate = $getChqBouncedetails['chequedepositdate'];

    $sql = array("UPDATE `tblfeecollectiondetail`, `tblfeecheque`
                SET `tblfeecollectiondetail`.feestatus = '2',
                `tblfeecheque`.chequestatus = '2'
                 WHERE `tblfeecollectiondetail`.feecollectionid = '$feecollectionid'
                AND `tblfeecollectiondetail`.feecollectionid =  `tblfeecheque`.feecollectionid 
                AND `tblfeecollectiondetail`.refundstatus = 0",
        "INSERT INTO `tblotherfeepenalties`( `instsessassocid`, `studentid`, `feecollectionid`, `amount`, 
                `status`) 
                VALUES ('$instsessassocid','$studentid','$feecollectionid','$ChqBounceAmt','0')
                ");

    $result = dbUpdate($sql);
}
