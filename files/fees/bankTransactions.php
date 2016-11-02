<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
 * Page details here: This page is used to create and import CSV files for bank transactions
 * Date Created: 03/02/2016
 * Updates here:
 */

/* Assign the breadcrumb page name for current page */

/* bread crumb page variables ends */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<div class="container">
    <?php renderMsg(); ?>
    <div class="span10">
        <?php
        if (isset($_GET['totalRecordsImported']) && (!empty($_GET['totalRecordsImported'])) && is_numeric($_GET['totalRecordsImported'])) {
            ?>   
            <div class="alert alert-success">
                <a href="../../reports/bankcsvreport.php">Total <strong><?php echo cleanVar($_GET['totalRecordsImported']) ?></strong> records are imported and total fees collection is Rs <strong><?php echo cleanVar($_GET['totalFeesCollected']) ?></strong></a>
            </div>
        <?php 
        }
        ?>
        <form action="<?php echo PROCESS_FORM ?>" method="post" id="imform" enctype="multipart/form-data">

            <div class="row"> 
                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">Student Name</span> 
                        <input type="text" class="form-control" name="studentname" id="studentname"  value ="<?php echo submitFailFieldValue("studentname"); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default"  name="search" id="search">
                                <span class="glyphicon glyphicon-search" name="search" id="search" value='Search' > </span></a> 
                        </span>  
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">Class</span>
                        <select name="classid" id="classid" tabindex="7" class="form-control" >
                            <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">Section</span>
                        <select name="sectionid" id="sectionid" tabindex="8" class="form-control">
                            <?php echo populateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
                        </select>
                    </div>
                </div>

            </div>

            <span class="clearfix">&nbsp;</span>
            <div class="row">  
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date From</span>
                        <input type="date" name="monthstart" id="monthstart" class="form-control">
                    </div>
                </div>  
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">Date To</span>
                        <input type="date" name="monthend" id="monthend" class="form-control">
                    </div>
                </div> 
            </div> 

            <span class="clearfix">&nbsp;</span>

            <div class="row"> 
                <div class="control" align="right">
                    <div class="col-lg-6"> 
                        <input type="submit" class="btn btn-info" name="createcsv" id="createcsv" value="Download CSV"> 
                        <label class="btn btn-success" for="uploadcsv">
                            <input type="file" name="uploadcsv" id="uploadcsv" style="display:none;" onchange="this.form.submit()">
                            Upload CSV
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require_once VIEW_FOOTER;

function getSessionStartDate()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT  sessionstartdate , sessionenddate 
            FROM tblacademicsession as t1, 
            tblinstsessassoc as t2 
            WHERE t1.academicsessionid = t2.academicsessionid 
            AND t2.instsessassocid = $instsessassocid";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (isset($_SESSION['instsessassocid'])) {
            $_SESSION['sessionstartdate'] = $row['sessionstartdate'];
            $_SESSION['sessionenddate'] = $row['sessionenddate'];
        } else {
            $sessionDate['startdate'] = $row['sessionstartdate'];
            $sessionDate['enddate'] = $row['sessionenddate'];
            return $sessionDate;
        }
    }
}

function getStudentDetailsSql($instsessassocid, $format)
{
    switch ($format) {
        case "report-display":
            $details = cleanVar($_REQUEST);

            $sql = "SELECT  t1.studentid ,t1.scholarnumber, 
                    LOWER(CONCAT ( t1.firstname , ' ',  t1.middlename,' ',t1.lastname)) as studentname, 
                    UPPER(CONCAT ( t3.classdisplayname,'-', t4.sectionname)) as classname ,
                    t3.classid, t4.sectionid 
                    
            FROM    `tblstudent` AS t1, `tblstudentdetails` AS t2, `tblclassmaster` AS t3, `tblsection` AS t4, 
                    `tblclsecassoc` AS t5, `tblstudentacademichistory` AS t6 
            WHERE   t1.instsessassocid = $instsessassocid AND 
                    t1.studentid = t2.studentid AND 
                    t1.studentid = t6.studentid AND 
                    t6.clsecassocid = t5.clsecassocid AND 
                    t5.classid = t3.classid AND 
                    t5.sectionid = t4.sectionid  AND
                    t1.status = 1 AND t1.deleted != 1
                    ";
            if (!empty($details['firstname'])) {
                $sql .= " AND t1.firstname LIKE '$details[firstname]%'";
            }
            if (!empty($details['classid'])) {
                $sql .= " AND   t5.classid = '$details[classid]' ";
            }
            if (!empty($details['sectionid'])) {
                $sql .= " AND   t5.sectionid = '$details[sectionid]' ";
            }

            $sql .= " ORDER BY studentname";
            break;

// sql for creating csv for bank upload

        case "csv-generate":
            $details = cleanVar($_REQUEST);

            $sql = " SELECT t1.studentid ,t1.scholarnumber,  t2.mobile, t1.firstname, t1.middlename, t1.lastname, 
                        t4.instituteabbrevation,t6.classname, t7.sectionname, t6.classid, t7.sectionid
                   
                FROM `tblstudent` AS t1,
                `tblstudentcontact` AS t2,
                `tblinstsessassoc` AS t3,
                `tblinstitute` AS t4,
                `tblclsecassoc` AS t5,
                `tblclassmaster` AS t6,
                `tblsection` AS t7,
                `tblstudentacademichistory` AS t8
                
                WHERE t1.studentid = t2.studentid
                AND t3.instsessassocid = $instsessassocid
                AND t3.instsessassocid = t1.instsessassocid
                AND t3.instituteid = t4.instituteid
                AND t1.studentid = t8.studentid
                AND t5.clsecassocid = t8.clsecassocid
                AND t5.classid = t6.classid
                AND t5.sectionid = t7.sectionid";

            if (!empty($details['studentname'])) {
                $sql .= " AND t1.firstname LIKE '$details[studentname]%'";
            }
            if (!empty($details['classid'])) {
                $sql .= " AND   t5.classid = '$details[classid]' ";
            }
            if (!empty($details['sectionid'])) {
                $sql .= " AND   t5.sectionid = '$details[sectionid]' ";
            }


            // $sql .= "  LIMIT 4002, 100";
            break;
        // standard sql for reports including pagination, for display on page and not on report.

        default:

            $startPage = (!isset($_GET['page'])) ? 0 : ($_GET['page'] - 1) * ROW_PER_PAGE;

            $sql = "SELECT  t1.studentid ,t1.scholarnumber, 
                    LOWER(CONCAT ( t1.firstname , ' ',  t1.middlename,' ',t1.lastname)) as studentname, 
                    UPPER(CONCAT ( t3.classdisplayname,'-', t4.sectionname)) as classname ,
                    t3.classid, t4.sectionid 
            FROM    `tblstudent` AS t1, `tblstudentdetails` AS t2, `tblclassmaster` AS t3, `tblsection` AS t4, 
                    `tblclsecassoc` AS t5, `tblstudentacademichistory` AS t6 
            WHERE   t1.instsessassocid = $instsessassocid AND 
                    t1.studentid = t2.studentid AND 
                    t1.studentid = t6.studentid AND 
                    t6.clsecassocid = t5.clsecassocid AND 
                    t5.classid = t3.classid AND 
                    t5.sectionid = t4.sectionid  AND
                    t1.status=1 AND t1.deleted != 1 ";

            if (!empty($details['firstname'])) {
                $sql .= " AND t1.firstname LIKE '$details[firstname]%'";
            }
            if (!empty($details['classid'])) {
                $sql .= " AND   t5.classid = '$details[classid]' ";
            }
            if (!empty($details['sectionid'])) {
                $sql .= " AND   t5.sectionid = '$details[sectionid]' ";
            }
            $sql .= " ORDER BY studentname LIMIT $startPage," . ROW_PER_PAGE;

            break;
    }
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentDetails[] = $row;
        }
        return $studentDetails;
    } else {
        return 0;
    }
}

function getParentDetails($studentid)
{
    $sql = " SELECT t1.parentfirstname, t1.parentmiddlename, t1.parentlastname
            FROM `tblparent` AS t1,
           `tbluserparentassociation` AS t2

           WHERE t2.studentid = $studentid
           AND t1.parentid = t2.parentid";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $parentDetails[] = $row['parentfirstname'] . " " . $row['parentmiddlename'] . " " . $row['parentlastname'];
        }
        $parentDetails['totalParent'] = mysqli_num_rows($result);
        return $parentDetails;
    }
}

function createCSV()
{
    $csvArray = getDueFeeStructure();
    $fileName = $_SESSION['instsessassocid'] . '-upload2bank-' . date('d-m-y') . ".txt";
    ob_start();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename=' . $fileName);
    if (isset($csvArray)) {
        $fp = fopen('php://output', 'w');
        $headerDisplayed = false;
        foreach ($csvArray as $values) {
            if (!$headerDisplayed) {
                // Use the keys from $data as the titles
                $valkeys = array_map('strval', array_keys($values));
                fputcsv($fp, $valkeys, '|', '"');
                $headerDisplayed = true;
            }

            $val = array_map('strval', $values);
            fputcsv($fp, $values, '|', '"');
        }
        fclose($fp);
    }
    ob_flush();
    exit();
}

function getConveyanceAmount($studentId)
{
    $conveyanceAmount = null;
    $sqlConveyance = " SELECT t2.amount
                       FROM tblstudentdetails as t1, tblpickuppoint as t2 
                       WHERE t1.pickuppointid = t2.pickuppointid 
                       AND t1.studentid = $studentId ";

    $resConveyance = dbSelect($sqlConveyance);
    if (mysqli_num_rows($resConveyance) > 0) {
        $row = mysqli_fetch_assoc($resConveyance);
        $conveyanceAmount = $row['amount'];
    }
    return $conveyanceAmount;
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

function getSessionDuedates($classid)
{
    $sql = "SELECT t2.duedate FROM 
                `tblfeestructure` AS t1,
                `tblfeestructuredetails` AS t2 
                WHERE t1.classid = '$classid'
                AND t1.feestructureid = t2.feestructureid
                ORDER BY t2.duedate  ASC";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $duedates[$classid][] = $row['duedate'];
        }
        $duedateArray = array_unique($duedates[$classid]);
        return $duedateArray;
    } else {
        return 0;
    }
}

function getDueFeeStructure()
{
    $searchTerm = cleanVar($_REQUEST);
    $feeInstallments = array();
    // get all student details in form an array
    $installmentArray = array();
    $studentDetails = getStudentDetailsSql($_SESSION['instsessassocid'], "csv-generate");
    $duedateArray = $newArray = $finalArray = array();

    //check whether start date of the session is set, if not call the fucntion and set the session value.
    if (!isset($_SESSION['sessionstartdate']) && empty($_SESSION['sessionstartdate'])) {
        getSessionStartEndDate();
    }

    //assign the starts of the session date
    $rangeStartDate = $_SESSION['sessionstartdate'];
    $rangeEndDate = date('Y-m-d');
    foreach ($studentDetails as $key => $value) {
        $totalFeeInstallments = "
                SELECT t1.feestructureid, t1.feecomponentid, t2.feestructureid, t2.duedate, t2.amount
                FROM `tblfeestructure` as t1,
                `tblfeestructuredetails` as t2
                WHERE t1.classid = '$value[classid]'
                AND t1.instsessassocid = '$_SESSION[instsessassocid]'
                AND t1.feestructureid = t2.feestructureid
                AND t1.status = 1
                AND t2.duedate 
                NOT IN(
                SELECT t3.feeinstallment FROM `tblfeecollection` AS t1, 
                    `tblfeecollectiondetail` AS t2,
                    `tblfeeinstallmentdates` AS t3
                    
                    WHERE t1.studentid = $value[studentid]
                    AND t1.feecollectionid = t2.feecollectionid
                    AND t2.feecollectiondetailid = t3.feecollectiondetailid
                    
                )   ";

        // if the start date is given by search, overwrite the session start date and use the new date.

        if (isset($searchTerm['monthstart']) && !empty($searchTerm['monthstart'])) {
            $rangeStartDate = $searchTerm['monthstart'];
        }

        if (isset($searchTerm['monthend']) && !empty($searchTerm['monthend'])) {
            //set the start of the session date, taken from Session
            $rangeEndDate = $searchTerm['monthend'];
            $totalFeeInstallments .= " AND MONTH (t2.duedate) <= '" . date('m', strtotime($searchTerm['monthend'])) . "'";
            $totalFeeInstallments .= " AND YEAR (t2.duedate)  <= '" . date('Y', strtotime($searchTerm['monthend'])) . "'";
        }

        $totalFeeInstallments .= " HAVING t2.duedate >= '$rangeStartDate' AND t2.duedate <= '$rangeEndDate'";

        $result = dbSelect($totalFeeInstallments);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['studentid'] = $value['studentid'];
                $feeInstallments[] = $row;
            }
        }
        // get parentDetails using studentid

        $parentDetails = getParentDetails($value['studentid']);
        foreach ($parentDetails as $k => $parentname) {
            $parent[$k] = $parentname;
        }
        if ($parentDetails['totalParent'] == 2) {
            $studentDetails[$key]['fathername'] = $parent[0];
            $studentDetails[$key]['mothername'] = $parent[1];
        } else {
            $studentDetails[$key]['fathername'] = $parent[0];
            $studentDetails[$key]['mothername'] = "";
        }

        // get duedate for each class using classid

        $duedatesArray = getSessionDuedates($value['classid']);
        $i = 1;
        foreach ($duedatesArray as $duedateKey => $duedatevalue) {
            $installmentArray[$value['classid']][$duedatevalue] = romanNumerals($i);
            $i++;
        }
    }

    $feeInstallments = getStuFeeRule($feeInstallments);
    $studentCount = 0;
    // get prefix for appending along with scholar number for each student
    $scholarPrefix = scholarnoabbr();
    foreach ($feeInstallments as $k => $val) {
        foreach ($val as $arrk => $arrval) {
            $duedateArray[$k][$arrk] = array_sum($arrval['adjustedAmount']);
        }
    }

    foreach ($duedateArray as $key => $value) {
        $totalValue = 0;
        $lastkey = '';
        $fine = '';
        foreach ($value as $k => $val) {
            $totalValue += $val;

            //  get late fees amount using installment date (duedate) .
            $lateFees = LateFees($k, $val);
            $fine[] = array_pop($lateFees);
            $lastkey = $k;
        }
        $lateFees = array_sum($fine);
        $newArray[$key][$lastkey] = $totalValue;
        $newArray[$key]["fine"] = $lateFees;
    }




    $sno = 1;
    foreach ($studentDetails as $key => $value) {
        // get prefix for appending along with scholar number for each student
        $scholarPrefix = scholarnoabbr();
        if (array_key_exists($value['studentid'], $newArray)) {
            $conveyancefees = getConveyanceAmount($key);
            $finalArray[$value['studentid']]["Mobile Number"] = $value['mobile'];
            $finalArray[$value['studentid']]["Student Name"] = ucfirst($value['firstname'] . " " . $value['middlename'] . " " . $value['lastname']);
            $finalArray[$value['studentid']]["Father's Name"] = ucfirst($value['fathername']);
            $finalArray[$value['studentid']]["Mother's Name"] = ucfirst($value['mothername']);
            $finalArray[$value['studentid']]["Branch"] = $value['instituteabbrevation'];
            $finalArray[$value['studentid']]["Class"] = $value['classname'];
            $finalArray[$value['studentid']]["Section"] = $value['sectionname'];
            $finalArray[$value['studentid']]["Dummy Installment"] = 0;
            $finalArray[$value['studentid']]["Scholar No"] = $scholarPrefix . $value['scholarnumber'];
            if (is_numeric(strpos(key($newArray[$value['studentid']]), "-"))) {
                $insatllmentNo = getInstallmentNumber($value['classid'], key($newArray[$value['studentid']]));
                $finalArray[$value['studentid']]["Installment Number"] = rtrim($insatllmentNo, ",");
                $finalArray[$value['studentid']]["Payment due date"] = date('d/m/Y', strtotime(key($newArray[$value['studentid']])));
                $finalArray[$value['studentid']]["Tuition Fee"] = $newArray[$value['studentid']][key($newArray[$value['studentid']])];
            }

            $finalArray[$value['studentid']]["Conveyance"] = $conveyancefees;
            $finalArray[$value['studentid']]["Fine"] = $newArray[$value['studentid']]['fine'];
            $finalArray[$value['studentid']]["serial number"] = $sno;

            $sno++;
        }
    }

    return $finalArray;
}

function getStuFeeRule($feeInstallmentArray)
{
    foreach ($feeInstallmentArray as $key => $value) {
        $feeInstallmentArray[$key]['feerulename'] = "";
        $feeInstallmentArray[$key]['feeruleid'] = "";
        $feeInstallmentArray[$key]['originalAmount'] = $feeInstallmentArray[$key]['amount'];
        $feeInstallmentArray[$key]['adjustedAmount'] = $feeInstallmentArray[$key]['amount'];

        $sqlFeeRule = " SELECT   t2.feeruleid, t2.feerulename, t3.feecomponentid,
                        t3.feerulemodeid, t3.feeruletype, t3.feeruleamount
                FROM    tblstudfeeruleassoc as t1,  tblfeerule as t2,  tblfeeruledetail as t3 
                WHERE   t1.feeruleid = t2.feeruleid 
                AND     t2.feeruleid = t3.feeruleid 
                AND     t1.studentid = $value[studentid]
                AND     t1.associationstatus = 1 
                AND     t2.feerulestatus = 1 ";

        $totalAdjAmt = $totalFullAmt = 0;
        $resFeeRule = dbSelect($sqlFeeRule);

        //if fee rule is applicable on the student, process further
        if (mysqli_num_rows($resFeeRule) > 0) {
            while ($row = mysqli_fetch_assoc($resFeeRule)) {
                $updatedFeesArray = feeRuleProcessing($value['feecomponentid'], $row, $value['amount']);
                if ($updatedFeesArray != 0) {
                    $feeInstallmentArray[$key]['adjustedAmount'] = $updatedFeesArray['feecomponentAmount'];
                    $feeInstallmentArray[$key]['feerulename'] = $updatedFeesArray['feerulename'];
                    $feeInstallmentArray[$key]['feeruleid'] = $updatedFeesArray['feeruleid'];
                }
            }
        }
        unset($feeInstallmentArray[$key]['amount']);
    }


    $holderArray = array();
    foreach ($feeInstallmentArray as $key => $value) {
        $holderArray[$value['studentid']][$value['duedate']]['feestructureid'][] = $value['feestructureid'];
        $holderArray[$value['studentid']][$value['duedate']]['feecomponentid'][] = $value['feecomponentid'];
        $holderArray[$value['studentid']][$value['duedate']]['adjustedAmount'][] = $value['adjustedAmount'];
        $holderArray[$value['studentid']][$value['duedate']]['originalAmount'][] = $value['originalAmount'];
        $holderArray[$value['studentid']][$value['duedate']]['feerulename'][] = $value['feerulename'];
        $holderArray[$value['studentid']][$value['duedate']]['feeruleid'][] = $value['feeruleid'];

        if (!isset($holderArray[$value['studentid']][$value['duedate']]['TotalAdjustedAmount'])) {
            $holderArray[$value['studentid']][$value['duedate']]['TotalAdjustedAmount'] = $value['adjustedAmount'];
        } else {
            $holderArray[$value['studentid']][$value['duedate']]['TotalAdjustedAmount'] += $value['adjustedAmount'];
        }
    }

    return $holderArray;
}

function feeRuleProcessing($feecomponentid, $feeruledetails, $feecomponentAmount)
{
    if ($feecomponentid == $feeruledetails['feecomponentid']) {
        if ($feeruledetails['feeruletype'] == "261") {
            if ($feeruledetails['feerulemodeid'] == "263") {  // 264 = flat ; 261 = discont
                $returnArray['feecomponentAmount'] = $feecomponentAmount - (($feecomponentAmount * $feeruledetails['feeruleamount'] / 100));
                $returnArray['feerulename'] = $feeruledetails['feerulename'];
                $returnArray['feeruleid'] = $feeruledetails['feeruleid'];
            } else {
                $returnArray['feecomponentAmount'] = $feecomponentAmount - $feeruledetails['feeruleamount'];
                $returnArray['feerulename'] = $feeruledetails['feerulename'];
                $returnArray['feeruleid'] = $feeruledetails['feeruleid'];
            }
        } else {
            if ($feeruledetails['feerulemodeid'] == "263") {
                $returnArray['feecomponentAmount'] = $feecomponentAmount + ($feecomponentAmount * $feeruledetails['feeruleamount'] / 100);
                $returnArray['feerulename'] = $feeruledetails['feerulename'];
                $returnArray['feeruleid'] = $feeruledetails['feeruleid'];
            } else {
                $returnArray['feecomponentAmount'] = $feecomponentAmount + $feeruledetails['feeruleamount'];
                $returnArray['feerulename'] = $feeruledetails['feerulename'];
                $returnArray['feeruleid'] = $feeruledetails['feeruleid'];
            }
        }
        return $returnArray;
    } else {
        return 0;
    }
}

function LateFees($duedate, $installmentamount)
{
    $feeamount = 0;
    $totaldays = 0;
    $otherFeeHead = "LATE FEES";
    $otherFeeDetails = otherFeeSql($otherFeeHead);

    if ($duedate < date('Y-m-d')) {
        $datediff = date_diff(date_create($duedate), date_create(date('Y-m-d')));
        $totaldays += $datediff->format("%R%a days");
    }
    foreach ($otherFeeDetails as $key => $value) {
        $calcAmount = OtherFeeCalculate($value['chargemode'], $value['otherfeetype'], $value['frequency'], $value['amount'], $installmentamount, $totaldays);
    }
    return ($calcAmount);
}

function otherFeeSql($otherfeehead = null)
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT *
            FROM `tblfeeothercharges` AS t1, 
            `tblfeeotherchargesdetails` AS t2

            WHERE t1.instsessassocid = $instsessassocid
            AND t1.feeotherchargesid = t2.feeotherchargesid
            AND t1.status = 1
            AND t1.deleted != 1
                 ";
    if (!empty($otherfeehead)) {
        $sql .= " AND t1.otherfeehead LIKE '$otherfeehead'";
    }
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $otherfeeDetails[] = $row;
        }
        return($otherfeeDetails);
    } else {
        return 0;
    }
}

function OtherFeeCalculate($chargemode, $otherfeetype, $frequency, $amount, $feeamount, $totaldays)
{
    $updatedAmt = 0;
    //echoThis($totaldays); die;
    if (!empty($amount)) {
        if (strtolower($chargemode) == 263) {
            //daily basis.
            if ($frequency == 303) {
                $dueAmount = (($amount / 100) * $duedate) * $feeamount;
                $diffAmount = $dueAmount;
            }
            //weekly basis.
            elseif ($frequency == 302) {
                $daysfromWeek = ceil($duedate / 7);
                $dueAmount = (($amount / 100) * $daysfromWeek) * $feeamount;
                $diffAmount = $dueAmount;
            }
            return $diffAmount;
        } else {
            $calculatedAmt = OtherFeesCalculateAmount($totaldays, $amount, $frequency);
            return $calculatedAmt;
        }
    } else {
        return 0;
    }
}

function OtherFeesCalculateAmount($totaldays, $amount, $frequency)
{
    switch (strtolower($frequency)) {
        // Calculate for Daily (303 = Daily)
        case '303':
            $dueAmount = ($totaldays * $amount);
            $diffAmount[] = $dueAmount;
            return $diffAmount;
            break;
        // Calculate for Weekly (302 = Weekly)
        case '302':
            $daysfromWeek = ceil($totaldays / 7); //echoThis($daysfromWeek ); die;
            $dueAmount = (($daysfromWeek) * $amount);
            $diffAmount[] = $dueAmount;
            return $diffAmount;
            break;

        default:
            return $amount;
    } //end of switch statement
}

/* * ********************************************************************************************** */
/* All function's set starting from here are used for uploading csv file
 * recieved from ICICI bank
 */

/* This function reads the xml/CSV file provided by bank
 * and create a multidimensional php filtered array ( ie array without empty array elements)
 */

function uploadData($filename)
{
    require_once '../../PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($filename);

    //Itrating through all the sheets in the excel workbook and storing the array data
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
    }
    $removeFlag = false;

    foreach ($arrayData as $key => $value) {
        foreach ($value as $k => $val) {
            // unset first array element  as it contains column name only
            if (!$removeFlag) {
                array_shift($val);
                $removeFlag = true;
            } else {
                $holderArray[] = $val;
            }
        }
    }
    // unset empty array element
    $finalArray = array_map('array_filter', $holderArray);
    $finalArray = array_filter($finalArray);
    //echoThis($finalArray);die;
    foreach ($finalArray as $key => $value) {
        if (isset($value[9]) && !empty($value[9])) {
            $scholarNoArray[] = substr($value[9], 4);
        }
    }
    //echothis($intAbbrevArray); die;
    $studentDetails = studentDetailsSql($scholarNoArray);
     $sql = feeCollectionSQL($studentDetails, $finalArray);
     
    // getting value of total records imported and total fees collected

    $totalRecordsImported = $sql['totalRecordsImported'];
    $totalFeesCollected = $sql ['totalFeesCollected'];
    unset($sql['totalRecordsImported']);
    unset($sql['totalFeesCollected']);
    
    $result = dbInsert($sql);
    // header("Location :bankTransactions.php?s=42");
    echo "<script type=\"text/javascript\">
                window.location.href = 'bankTransactions.php?totalRecordsImported=$totalRecordsImported&totalFeesCollected=$totalFeesCollected';
            </script>";
}

/* This function return an array with complete student details
 * required for processing ; using scholar ID's Array and
 * Institute abbrevation array.
 */

function studentDetailsSql($scholarNoArray)
{
    foreach ($scholarNoArray as $key => $value) {
        $sql = "SELECT t1.studentid, t1.scholarnumber, t1.instsessassocid,  t1.firstname, 
                t1.middlename, t1.lastname, t6.clsecassocid, t8.sessionname
                  
                FROM `tblstudent` AS t1, 
                `tblstudentacademichistory` AS t2, 
                `tblstudentdetails` AS t3,
                `tblclassmaster` AS t4, 
                `tblsection` AS t5,
                `tblclsecassoc` AS t6,
                `tblinstsessassoc` As t7,
                `tblacademicsession` AS t8
               
                WHERE t1.scholarnumber = $value
                AND t1.instsessassocid = $_SESSION[instsessassocid]
                AND t1.instsessassocid = t7.instsessassocid
                AND t7.academicsessionid = t8.academicsessionid
                AND t1.studentid = t2.studentid
                AND t1.studentid = t3.studentid
                AND t2.clsecassocid = t6.clsecassocid
                AND t6.classid = t4.classid
                AND t6.sectionid = t5.sectionid
                AND t1.status = 1
                AND t1.deleted != 1
		  
		  ";
        $result = dbSelect($sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $tempArray = $row;
            }
            $studentdetail[$key] = $tempArray;
        }
    }

    return $studentdetail;
}

/* This function return an final sql array with complete student details
 * along with there deposited fee details.
 */

function feeCollectionSQL($studentData = null, $feeDetailsArray)
{
    $remarks = "Fee collected by ICICI bank";
    //echoThis($feeDetailsArray); die;
    $flag = false;
    $totalRecordsImported = count($feeDetailsArray);
    $totalFeeCollected = 0;
    foreach ($feeDetailsArray as $key => $value) {
        if (array_key_exists($key, $studentData)) {

            // generate reciept.no for the each transaction

            if (!$flag) {
                $recieptid = GenerateRecieptNumber($value[5], $studentData[$key]['sessionname']);
                //$recieptid = $value[5] . "/" . $studentData[$key]['sessionname'] . "/" . $recieptcode;
                $flag = true;
            } else {
                $recieptid += 1;
                $recieptid = $value[5] . "/" . $studentData[$key]['sessionname'] . "/" . $recieptid;
            }

            /* converting feeinstallment date coming in 'd/m/Y' format to 'Y-m-d'
             * format (preferred for db entry)
             */

            $feeinstallmentdate = str_replace('/', '-', $value[8]);
            $feeinstallmentdate = date('Y-m-d', strtotime($feeinstallmentdate));

            // creating variable used in making insert sql for  tblbanktrandetails

            $icid = $value[0];
            $scholar_no = substr($value[9], strpos($value[9], "-") + 1);
            $mobile = $value[1];
            $student_name = $value[2];
            $father_name = $value[3];
            $mother_name = $value[4];
            $branch = $value[5];
            $class = $value[6];
            $section = $value[7];

            /* The feedetails array is filtered using array_filter function(system defined)
             * for removing empty cloumn name from final array.
             * This action removes few fields(cloumn name) from final array if it's value is empty or zero
             */

            if (isset($value[14])) {
                $installment_number = $value[14];
            } else {
                $installment_number = 0;
            }

            if (isset($value[11])) {
                $tuition_fee = $value[11];
            } else {
                $tuition_fee = 0;
            }

            if (isset($value[13])) {
                $fine = $value[13];
            } else {
                $fine = 0;
            }

            if (isset($value[12])) {
                $conveyance = $value[12];
            } else {
                $conveyance = 0;
            }


            $serialno = $value[15];
            $tranid = $value[16];
            $merchant_opted_mode = $value[17];
            // first replace the '/' with '-' and convert the date format of d/m/y to y-m-d //
            $tran_date = date('Y-m-d', strtotime(str_replace('/', '-', $value[18])));
            $tran_amount = '';
            if (isset($value[19])) {
                $tran_amount = $value[19];
            }

            /*
             * Calculating Total Fees Collecetion
             * by adding each student per iteration
             */
            $totalFeeCollected += $tuition_fee + $fine + $conveyance;

            if (isset($value[20])) {
                $processingfee = $value[20];
            } else {
                $processingfee = 0;
            }

            if (isset($value[21])) {
                $servicetax = $value[21];
            } else {
                $servicetax = 0;
            }

            $settlement_date = '';
            if (isset($value[24]) && $value[24] == "Not Initiated") {
                $settlement_date = $value[24];
            } else {
                // first replace the '/' with '-' and convert the date format of d/m/y to y-m-d //
                $settlement_date = date('Y-m-d', strtotime(str_replace('/', '-', $value[24])));
            }
            $recon_date = '';
            if (isset($value[23]) && $value[23] == "Not Initiated") {
                $recon_date = $value[23];
            } else {
                // first replace the '/' with '-' and convert the date format of d/m/y to y-m-d //
                $recon_date = date('Y-m-d', strtotime(str_replace('/', '-', $value[23])));
            }

            $payer_opted_mode = $value[22];
            $ref_no = '';
            if (isset($value[25])) {
                $ref_no = $value[25];
            }
            $dynamicurl = $comments = '';
            if (isset($value[26])) {
                $dynamicurl = $value[24];
                $comments = $value[26];
            }
            
           

            $feeInstallmentAmount = $tuition_fee;


            $studentid = $studentData[$key]['studentid'];
            $clsecassocid = $studentData[$key]['clsecassocid'];
            $instsessassocid = $studentData[$key]['instsessassocid'];

            $sql[] = "INSERT INTO `tblfeecollection`(`studentid`,`instsessassocid`,`clsecassocid`,`receiptid`,`remarks`)
                      VALUES('$studentid','$instsessassocid','$clsecassocid','$recieptid','$remarks');";

            $sql[] = "SET @last_insert_id_1 = LAST_INSERT_ID();";

            // getBankCollectionMode function return's system defined FeeCollection Mode ID's
           
            $collectionMode = getMasterId($value[21], "master");

            // Make Collection Type as globally DEFINE in config file


            $collectionType = '316';
            if ($feeInstallmentAmount != 0) {
                $sql[] = "INSERT INTO `tblfeecollectiondetail`(`feecollectionid`,`feeinstallmentamount`,
                        `feemodeid`,`feestatus`, `collectiontype`)
                VALUES(@last_insert_id_1,'$feeInstallmentAmount','$collectionMode','1', '$collectionType');";


                $sql[] = "SET @last_insert_id_2 = LAST_INSERT_ID();";

                $sql [] = "INSERT INTO `tblfeeinstallmentdates`(`feecollectiondetailid`,`feeinstallment`,`status`)
                        VALUES(@last_insert_id_2,'$feeinstallmentdate','1');";
            }
            if ($fine != 0) {
                $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,`feeinstallmentamount`,
                       `feemodeid`,`feestatus`)
                      VALUES(@last_insert_id_1 ,'1','$fine','$collectionMode','1');";

                $sql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";

                $sql[] = "INSERT INTO `tblfeepenaltydetails`( `feecollectiondetailid`, `feeinstallmentid`, `status`) 
                            VALUES(@last_insert_id_3,'$feeinstallmentdate','1');";
            }

            if ($conveyance != 0) {
                $sql[] = "INSERT INTO `tblfeecollectiondetail`( `feecollectionid`, `collectiontype`,`feeinstallmentamount`,
                       `feemodeid`,`feestatus`)
                      VALUES(@last_insert_id_1 ,'3','$conveyance','$collectionMode','1');";

                $sql[] = "SET @last_insert_id_3 = LAST_INSERT_ID();";

                $sql[] = "INSERT INTO `tblfeepenaltydetails`( `feecollectiondetailid`, `feeinstallmentid`,`status`)
                             VALUES(@last_insert_id_3 ,'$feeinstallmentdate','1');";
            }

            $class = getMasterId($class, "class");
            $section = getMasterId($section, "section");


            $sql[] = "INSERT INTO `tblbanktransdetails`(`icid`, `feecollectionid`, `scholar_no`, `mobile`,
                      `student_name`, `father_name`, `mother_name`, `branch`, `class`, `section`, `payment_due_date`,
                      `installment_number`, `serial_no`, `tuition_fee`, `conveyance`, `fine`, `tranid`, `merchant_opted_mode`, 
                      `tran_date`, `tran_amount`, `processing_fee`, `servicetax`, `payer_opted_mode`, `recon_date`, `settlement_date`,
                      `ref_no`, `dyanamicurl`) VALUES 
                      
                    ( '$icid', @last_insert_id_1, '$scholar_no', '$mobile', '$student_name', '$father_name', 
                      '$mother_name', '$branch','$class','$section','$feeinstallmentdate', 
                      '$installment_number', '$serialno', '$tuition_fee','$conveyance','$fine', '$tranid', '$merchant_opted_mode',  
                      '$tran_date', '$tran_amount', '$processingfee',  '$servicetax', '$collectionMode',
                      '$recon_date', '$settlement_date' ,'$ref_no', '$dynamicurl'
                    );"
            ;
        }
    }
    $sql['totalRecordsImported'] = $totalRecordsImported;
    $sql['totalFeesCollected'] = $totalFeeCollected;

    return $sql;
}

function getMasterId($collectionname, $type)
{
    switch ($type) {
        case "master":
            $sql = "SELECT t2.mastercollectionid 
              FROM `tblmastercollectiontype` AS t1,
              `tblmastercollection` AS t2
              
              WHERE t2.collectionname = '$collectionname'
              AND t1.mastercollectiontypeid = t2.mastercollectiontypeid
              ";

            $result = dbSelect($sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $mastercollectionid = $row['mastercollectionid'];
                }
                return $mastercollectionid;
            } else {
                return 0;
            }
            break;
        case "class":
            $sql = "SELECT `classid` FROM `tblclassmaster` WHERE `classname` = '$collectionname' ";
            $result = dbSelect($sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $mastercollectionid = $row['classid'];
                }
                return $mastercollectionid;
            } else {
                return 0;
            }
            break;

        case "section":
            $sql = "SELECT `sectionid` FROM `tblsection` WHERE `sectionname` = '$collectionname' ";
            $result = dbSelect($sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $mastercollectionid = $row['sectionid'];
                }
                return $mastercollectionid;
            } else {
                return 0;
            }
            break;
    }
}


